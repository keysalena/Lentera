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
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    
    public function dashboard()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $siswa = \App\Models\Siswa::where('id_user', $user->id)->firstOrFail();

        
        $eksplorasi = \App\Models\Eksplorasi::where('id_siswa', $siswa->id_siswa)->orderBy('created_at', 'desc')->first();

        
        $hasGambar = false;
        $hasNilai = false;
        $statusEksplorasi = 'belum_mulai';

        
        if ($eksplorasi) {
            $statusEksplorasi = $eksplorasi->status;

            $hasGambar = \App\Models\EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->exists();
            $hasNilai = \App\Models\NilaiAkademik::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->whereNotNull('nilai')->exists();
        }

        return view('siswa.ringkasan', compact('siswa', 'hasGambar', 'hasNilai', 'statusEksplorasi', 'eksplorasi'));
    }

    
    
    
    public function input(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

        
        $eksplorasi = Eksplorasi::where('id_siswa', $siswa->id_siswa)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$eksplorasi) {
            $eksplorasi = Eksplorasi::create([
                'id_siswa' => $siswa->id_siswa,
                'status' => 'draft'
            ]);
        }

        
        $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
        $nilai = NilaiAkademik::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->whereNotNull('nilai')->pluck('nilai', 'id_mapel')->toArray();
        $skor = SkorKemampuan::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->pluck('skor', 'id_kemampuan')->toArray();

        
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

        
        if ($request->step == 1) {
            $request->validate(['tulisan_tangan' => 'required|image|mimes:jpeg,png,jpg|max:5120']);

            if ($request->hasFile('tulisan_tangan') && $request->file('tulisan_tangan')->isValid()) {
                $file = $request->file('tulisan_tangan');
                $nama_file = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/uploads/eksplorasi');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $nama_file);
                $path = 'uploads/eksplorasi/' . $nama_file;

                EksplorasiGambar::updateOrCreate(
                    ['id_eksplorasi' => $eksplorasi->id_eksplorasi],
                    ['gambar' => $path]
                );

                return redirect()->route('siswa.input')->with('success', 'Dokumen reflektif berhasil disimpan! Silakan isi nilai rapor.');
            }
        }

        
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

        
        if ($request->step == 3) {

            
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'kemampuan_') && $value !== null && $value !== '') {
                    $id_kemampuan = str_replace('kemampuan_', '', $key);
                    SkorKemampuan::updateOrCreate(
                        ['id_eksplorasi' => $eksplorasi->id_eksplorasi, 'id_kemampuan' => $id_kemampuan],
                        ['skor' => $value]
                    );
                }
            }

            
            if ($request->has('action_finalisasi')) {

                
                $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
                if (!$gambar) return back()->with('error', 'Gambar belum diunggah! Mohon lengkapi Tahap 1.');
                $imagePath = storage_path('app/public/' . $gambar->gambar);

                if (!file_exists($imagePath)) return back()->with('error', 'File gambar fisik tidak ditemukan di server.');

                
                $nilaiAkademik = NilaiAkademik::with('mapel')->where('id_eksplorasi', $eksplorasi->id_eksplorasi)->get();
                $akademikData = [];
                foreach ($nilaiAkademik as $n) {
                    $key = strtolower(str_replace(' ', '_', $n->mapel->nama_mapel));
                    $akademikData[$key] = (float) $n->nilai;
                }

                
                $skorKemampuan = SkorKemampuan::with('kemampuan')->where('id_eksplorasi', $eksplorasi->id_eksplorasi)->get();
                $minatData = [];
                foreach ($skorKemampuan as $s) {
                    
                    $key = $s->kemampuan->kode_item;

                    
                    if (!empty($key)) {
                        $minatData[$key] = (int) $s->skor;
                    }
                }

                
                try {
                    $response = Http::timeout(60) 
                        ->attach('file', file_get_contents($imagePath), 'tulisan_siswa.jpg')
                        
                        ->post('http://localhost:8000/predict', [
                            
                            'akademik' => json_encode($akademikData, JSON_FORCE_OBJECT),
                            'minat'    => json_encode($minatData, JSON_FORCE_OBJECT),
                        ]);

                    if ($response->successful()) {
                        $hasilJson = $response->json();

                        
                        $gambar->update(['hasil_ocr' => json_encode($hasilJson)]);

                        
                        $eksplorasi->update(['status' => 'selesai']);
                        $siswa->update(['status_data' => 'lengkap']);

                        return redirect()->route('siswa.hasil')->with('success', 'Analisis berhasil! Berikut adalah hasil pemetaan AI LENTERA.');
                    } else {
                        
                        return back()->with('error', 'Gagal memproses data di server AI: ' . $response->body());
                    }
                } catch (\Exception $e) {
                    return back()->with('error', 'Koneksi ke server AI terputus. Pastikan FastAPI berjalan di port 8000. Error: ' . $e->getMessage());
                }
            }

            
            return redirect()->route('siswa.input')->with('success', 'Data potensi minat berhasil disimpan!');
        }
    }

    public function hasil()
    {
        $user = Auth::user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

        $eksplorasi = Eksplorasi::where('id_siswa', $siswa->id_siswa)->orderBy('created_at', 'desc')->first();

        
        if (!$eksplorasi || $eksplorasi->status == 'draft') {
            return redirect()->route('siswa.input')->with('error', 'Anda belum menyelesaikan finalisasi analisis data.');
        }

        $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();

        
        $ml_data = $gambar && $gambar->hasil_ocr ? json_decode($gambar->hasil_ocr, true) : null;

        return view('siswa.hasil', compact('eksplorasi', 'ml_data'));
    }

    
    public function profil()
    {
        $user = Auth::user();
        
        $siswa = Siswa::where('id_user', $user->id)->first();

        return view('siswa.profil', compact('user', 'siswa'));
    }

    
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        
        $request->validate([
            'nama' => 'required|string|max:150',
            'nisn' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'kode_lisensi' => 'nullable|string',
        ]);

        
        $user->update(['nama' => $request->nama]);

        $siswa = Siswa::where('id_user', $user->id)->first();
        if ($siswa) {
            $siswa->update([
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        
        if ($request->filled('kode_lisensi') && is_null($user->id_sekolah)) {
            $sekolah = Sekolah::where('kode_lisensi_siswa', $request->kode_lisensi)->first();

            if ($sekolah) {
                
                $user->update(['id_sekolah' => $sekolah->id_sekolah]);

                return redirect()->back()->with('success', 'Profil diperbarui & Lisensi Sekolah berhasil diaktifkan!');
            } else {
                return redirect()->back()->with('error', 'Kode lisensi tidak valid, fitur konseling tidak dapat diaktifkan.');
            }
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan catatan kami.']);
        }

        
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui!');
    }
    
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

        
        $userIdsGuru = \App\Models\User::where('id_role', 2)
            ->where('id_sekolah', $user->id_sekolah)
            ->pluck('id');

        $daftarGuru = \App\Models\Guru::whereIn('id_user', $userIdsGuru)->get();

        
        foreach ($daftarGuru as $g) {
            $g->akun = \App\Models\User::find($g->id_user);
        }

        return view('siswa.konsultasi', compact('eksplorasi', 'konsultasiAktif', 'riwayatKonsultasi', 'siswa', 'daftarGuru'));
    }

    
    public function storeKonsultasi(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $siswa = \App\Models\Siswa::where('id_user', $user->id)->first();

        $request->validate([
            'id_eksplorasi' => 'required',
            'id_guru' => 'required', 
            'topik' => 'required|string',
            'alasan_siswa' => 'required|string',
        ]);

        
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
    public function panduan()
    {
        
        return view('siswa.panduan');
    }
}
