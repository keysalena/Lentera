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
use App\Models\Sekolah;
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
                        $eksplorasi->update(['status' => 'selesai']);
                        $siswa->update(['status_data' => 'lengkap']);

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

        // 1. Validasi
        $request->validate([
            'nama' => 'required|string|max:150',
            'nisn' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'kode_lisensi' => 'nullable|string',
        ]);

        // 2. Update data dasar (Nama di Users, NISN/JK di Siswa)
        $user->update(['nama' => $request->nama]);

        $siswa = Siswa::where('id_user', $user->id)->first();
        if ($siswa) {
            $siswa->update([
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        // 3. LOGIKA UPDATE LISENSI (Hanya jika siswa belum punya sekolah)
        if ($request->filled('kode_lisensi') && is_null($user->id_sekolah)) {
            $sekolah = Sekolah::where('kode_lisensi_siswa', $request->kode_lisensi)->first();

            if ($sekolah) {
                // Update id_sekolah pada tabel users
                $user->update(['id_sekolah' => $sekolah->id_sekolah]);

                return redirect()->back()->with('success', 'Profil diperbarui & Lisensi Sekolah berhasil diaktifkan!');
            } else {
                return redirect()->back()->with('error', 'Kode lisensi tidak valid, fitur konseling tidak dapat diaktifkan.');
            }
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    // Halaman Konsultasi Karier (Siswa)
    public function konsultasi()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $siswa = \App\Models\Siswa::where('id_user', $user->id)->first();

        $eksplorasi = null;
        if ($siswa) {
            $eksplorasi = \App\Models\Eksplorasi::where('id_siswa', $siswa->id_siswa)
                ->where('status', 'selesai')
                ->orderBy('created_at', 'desc')
                ->first();
        }

        $konsultasiAktif = null;
        $riwayatKonsultasi = [];

        if ($siswa) {
            $konsultasiAktif = \App\Models\Konsultasi::where('id_siswa', $siswa->id_siswa)
                ->whereIn('status', ['Menunggu', 'Dijadwalkan'])
                ->first();

            $riwayatKonsultasi = \App\Models\Konsultasi::where('id_siswa', $siswa->id_siswa)
                ->where('status', 'Selesai')
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        // --- FITUR BARU: Ambil Daftar Guru BK di Sekolah yang Sama ---
        $userIdsGuru = \App\Models\User::where('id_role', 2)
            ->where('id_sekolah', $user->id_sekolah)
            ->pluck('id');

        $daftarGuru = \App\Models\Guru::whereIn('id_user', $userIdsGuru)->get();

        // Gabungkan nama dari tabel User ke koleksi Guru secara manual
        foreach ($daftarGuru as $g) {
            $g->akun = \App\Models\User::find($g->id_user);
        }

        return view('siswa.konsultasi', compact('eksplorasi', 'konsultasiAktif', 'riwayatKonsultasi', 'siswa', 'daftarGuru'));
    }

    // Fungsi untuk memproses ajuan konsultasi baru
    public function storeKonsultasi(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $siswa = \App\Models\Siswa::where('id_user', $user->id)->first();

        $request->validate([
            'id_eksplorasi' => 'required',
            'id_guru' => 'required', // Wajib memilih Guru BK
            'topik' => 'required|string',
            'alasan_siswa' => 'required|string',
        ]);

        // Cek apakah ada jadwal konsultasi yang masih menggantung
        $cekAktif = \App\Models\Konsultasi::where('id_siswa', $siswa->id_siswa)
            ->whereIn('status', ['Menunggu', 'Dijadwalkan'])
            ->first();
        if ($cekAktif) {
            return back()->with('error', 'Anda masih memiliki jadwal konsultasi yang sedang berjalan.');
        }

        $topScore = 0;
        $eksplorasi = \App\Models\Eksplorasi::find($request->id_eksplorasi);

        if ($eksplorasi && $eksplorasi->status == 'selesai') {
            $gambar = \App\Models\EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
            if ($gambar && $gambar->hasil_ocr) {
                $ml_data = json_decode($gambar->hasil_ocr, true);
                $topScore = $ml_data['rekomendasi_jurusan'][0]['match_score'] ?? 0;
            }
        }

        $tingkat_prioritas = 'Menengah';

        try {
            $systemInstruction = "Kamu adalah asisten psikologi untuk Guru Bimbingan Konseling. Tugasmu adalah menganalisis tingkat urgensi keluhan siswa berdasarkan skor kecocokan jurusan dan beban psikologis dari kalimat mereka. Jawab HANYA dengan satu kata mutlak: Tinggi, Menengah, atau Rendah. Dilarang memberikan penjelasan tambahan.";

            $userPrompt = "Skor kecocokan akademik siswa ini adalah {$topScore}%. Keluhan siswa: '{$request->alasan_siswa}'. Tentukan tingkat urgensi penanganannya sekarang.";

            $aiResponse = \Laravel\Ai\agent(instructions: $systemInstruction)->prompt($userPrompt);

            $cleanResponse = ucfirst(strtolower(trim(preg_replace('/[^a-zA-Z]/', '', $aiResponse))));

            if (in_array($cleanResponse, ['Tinggi', 'Menengah', 'Rendah'])) {
                $tingkat_prioritas = $cleanResponse;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Triage Agent Gagal: ' . $e->getMessage());
        }

        \App\Models\Konsultasi::create([
            'id_siswa' => $siswa->id_siswa,
            'id_eksplorasi' => $request->id_eksplorasi,
            'id_guru' => $request->id_guru,
            'topik' => $request->topik,
            'alasan_siswa' => $request->alasan_siswa,
            'tingkat_prioritas' => $tingkat_prioritas,
            'status' => 'Menunggu',
        ]);

        return back()->with('success', 'Pengajuan konsultasi berhasil dikirim! Silakan tunggu konfirmasi jadwal dari Guru BK yang Anda pilih.');
    }
}
