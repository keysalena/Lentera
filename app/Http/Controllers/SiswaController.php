<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Eksplorasi;
use App\Models\EksplorasiGambar;
use App\Models\NilaiAkademik;
use App\Models\SkorKemampuan;
use App\Models\MataPelajaran;
use App\Models\Kemampuan;
use Illuminate\Support\Facades\Http;

class SiswaController extends Controller
{
    // Halaman Dashboard Siswa
    public function dashboard()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $siswa = \App\Models\Siswa::where('id_user', $user->id)->firstOrFail();

        // Ambil data eksplorasi terbaru
        $eksplorasi = \App\Models\Eksplorasi::where('id_siswa', $siswa->id_siswa)->orderBy('created_at', 'desc')->first();

        // Siapkan variabel default
        $hasGambar = false;
        $hasNilai = false;
        $statusEksplorasi = 'belum_mulai';

        // Cek progres jika sudah ada draft/proses
        if ($eksplorasi) {
            $statusEksplorasi = $eksplorasi->status;

            $hasGambar = \App\Models\EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->exists();
            $hasNilai = \App\Models\NilaiAkademik::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->whereNotNull('nilai')->exists();
        }

        return view('siswa.ringkasan', compact('siswa', 'hasGambar', 'hasNilai', 'statusEksplorasi', 'eksplorasi'));
    }

    // =====================================================================
    // 1. MENAMPILKAN HALAMAN INPUT (DENGAN PROGRES)
    // =====================================================================
    public function input(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

        // Tarik data eksplorasi terbaru (jika tidak ada, buat draf baru)
        $eksplorasi = Eksplorasi::where('id_siswa', $siswa->id_siswa)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$eksplorasi) {
            $eksplorasi = Eksplorasi::create([
                'id_siswa' => $siswa->id_siswa,
                'status' => 'draft'
            ]);
        }

        // Tarik data progres yang sudah tersimpan di database
        $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
        $nilai = NilaiAkademik::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->whereNotNull('nilai')->pluck('nilai', 'id_mapel')->toArray();
        $skor = SkorKemampuan::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->pluck('skor', 'id_kemampuan')->toArray();

        // Tentukan tahap yang aktif berdasarkan kelengkapan data
        $step = 1;
        if ($gambar) $step = 2;
        if ($gambar && !empty($nilai)) $step = 3;

        $edit_step = $request->query('edit_step');
        $mapels = MataPelajaran::all();
        $kemampuans = Kemampuan::all();

        return view('siswa.input', compact('mapels', 'kemampuans', 'step', 'gambar', 'nilai', 'skor', 'edit_step', 'eksplorasi'));
    }

    // =====================================================================
    // 2. MEMPROSES PENYIMPANAN DATA PER TAHAP & FINALISASI ML
    // =====================================================================
    public function storeEksplorasi(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();
        $eksplorasi = Eksplorasi::where('id_siswa', $siswa->id_siswa)->orderBy('created_at', 'desc')->firstOrFail();

        // ── TAHAP 1: UPLOAD DOKUMEN TULISAN ──
        if ($request->step == 1) {
            $request->validate(['tulisan_tangan' => 'required|image|mimes:jpeg,png,jpg|max:5120']);

            if ($request->hasFile('tulisan_tangan') && $request->file('tulisan_tangan')->isValid()) {
                $file = $request->file('tulisan_tangan');
                $nama_file = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/uploads/eksplorasi');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Native move() untuk menghindari error Path Must Not Be Empty di XAMPP
                $file->move($destinationPath, $nama_file);
                $path = 'uploads/eksplorasi/' . $nama_file;

                EksplorasiGambar::updateOrCreate(
                    ['id_eksplorasi' => $eksplorasi->id_eksplorasi],
                    ['gambar' => $path]
                );

                return redirect()->route('siswa.input')->with('success', 'Dokumen reflektif berhasil disimpan! Silakan isi nilai rapor.');
            }
        }

        // ── TAHAP 2: SIMPAN PROGRES NILAI AKADEMIK ──
        if ($request->step == 2) {
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'mapel_') && $value !== null && $value !== '') {
                    $id_mapel = str_replace('mapel_', '', $key);
                    NilaiAkademik::updateOrCreate(
                        ['id_eksplorasi' => $eksplorasi->id_eksplorasi, 'id_mapel' => $id_mapel],
                        ['nilai' => $value]
                    );
                }
            }
            return redirect()->route('siswa.input')->with('success', 'Data nilai akademik berhasil diperbarui!');
        }

        // ── TAHAP 3: KEMAMPUAN ATAU FINALISASI KE API ML ──
        if ($request->step == 3) {

            // SIMPAN DATA KEMAMPUAN KE DB TERLEBIH DAHULU
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'kemampuan_') && $value !== null && $value !== '') {
                    $id_kemampuan = str_replace('kemampuan_', '', $key);
                    SkorKemampuan::updateOrCreate(
                        ['id_eksplorasi' => $eksplorasi->id_eksplorasi, 'id_kemampuan' => $id_kemampuan],
                        ['skor' => $value]
                    );
                }
            }

            // JIKA TOMBOL "FINALISASI" DITEKAN, TEMBAK KE FASTAPI
            if ($request->has('action_finalisasi')) {

                // 1. Ambil Path Gambar Asli
                $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
                if (!$gambar) return back()->with('error', 'Gambar belum diunggah! Mohon lengkapi Tahap 1.');
                $imagePath = storage_path('app/public/' . $gambar->gambar);

                if (!file_exists($imagePath)) return back()->with('error', 'File gambar fisik tidak ditemukan di server.');

                // 2. Format Data Akademik (JSON Array Key huruf kecil)
                $nilaiAkademik = NilaiAkademik::with('mapel')->where('id_eksplorasi', $eksplorasi->id_eksplorasi)->get();
                $akademikData = [];
                foreach ($nilaiAkademik as $n) {
                    $key = strtolower(str_replace(' ', '_', $n->mapel->nama_mapel));
                    $akademikData[$key] = (float) $n->nilai;
                }

                // 3. Format Data Kemampuan (JSON Array Key huruf kecil)
                $skorKemampuan = SkorKemampuan::with('kemampuan')->where('id_eksplorasi', $eksplorasi->id_eksplorasi)->get();
                $talentData = [];
                foreach ($skorKemampuan as $s) {
                    $key = strtolower(str_replace(' ', '_', $s->kemampuan->nama_kemampuan));
                    $talentData[$key] = (int) $s->skor;
                }

                // 4. Request Multipart ke FastAPI
                try {
                    $response = Http::timeout(60) // Tunggu maksimal 60 detik untuk ML memproses
                        ->attach('file', file_get_contents($imagePath), 'tulisan_siswa.jpg')
                        // ->post('https://gpf0gt5s-8001.asse.devtunnels.ms/predict', [
                        ->post('http://localhost:8000/predict', [
                            'akademik' => json_encode($akademikData),
                            'talent'   => json_encode($talentData),
                        ]);

                    if ($response->successful()) {
                        $hasilJson = $response->json();

                        // Simpan Full JSON hasil ML ke kolom hasil_ocr
                        $gambar->update(['hasil_ocr' => json_encode($hasilJson)]);

                        // Update Status Eksplorasi & Siswa
                        $eksplorasi->update(['status' => 'selesai']); // Gunakan 'selesai' untuk eksplorasi
                        $siswa->update(['status_data' => 'lengkap']); // Gunakan 'lengkap' sesuai ENUM tabel siswa

                        return redirect()->route('siswa.hasil')->with('success', 'Analisis berhasil! Berikut adalah hasil pemetaan AI LENTERA.');
                    } else {
                        // Jika FastAPI membalas Error 400/422/500
                        return back()->with('error', 'Gagal memproses data di server AI: ' . $response->body());
                    }
                } catch (\Exception $e) {
                    return back()->with('error', 'Koneksi ke server AI terputus. Pastikan FastAPI berjalan di port 8000. Error: ' . $e->getMessage());
                }
            }

            // JIKA HANYA SIMPAN PROGRES BIASA
            return redirect()->route('siswa.input')->with('success', 'Data potensi minat berhasil disimpan!');
        }
    }

    // =====================================================================
    // 3. MENAMPILKAN HALAMAN HASIL ANALISIS
    // =====================================================================
    public function hasil()
    {
        $user = Auth::user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

        $eksplorasi = Eksplorasi::where('id_siswa', $siswa->id_siswa)->orderBy('created_at', 'desc')->first();

        // Jika belum finalisasi, lempar kembali ke halaman input
        if (!$eksplorasi || $eksplorasi->status == 'draft') {
            return redirect()->route('siswa.input')->with('error', 'Anda belum menyelesaikan finalisasi analisis data.');
        }

        $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();

        // Dekode JSON string dari database kembali menjadi array PHP
        $ml_data = $gambar && $gambar->hasil_ocr ? json_decode($gambar->hasil_ocr, true) : null;

        return view('siswa.hasil', compact('eksplorasi', 'ml_data'));
    }

    // Halaman Kelengkapan Profil Siswa
    public function profil()
    {
        $user = Auth::user();
        // Ambil data spesifik siswa (NISN, Jenis Kelamin) dari tabel siswa
        $siswa = Siswa::where('id_user', $user->id)->first();

        return view('siswa.profil', compact('user', 'siswa'));
    }

    // Fungsi untuk menyimpan perubahan profil siswa
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:150',
            'nisn' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        // Update nama di tabel users
        $user->update(['nama' => $request->nama]);

        // Update NISN dan Jenis Kelamin di tabel siswa
        $siswa = Siswa::where('id_user', $user->id)->first();
        if ($siswa) {
            $siswa->update([
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
