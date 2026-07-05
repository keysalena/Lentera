<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Eksplorasi;
use App\Models\NilaiAkademik;
use App\Models\EksplorasiGambar;
use App\Mail\NotifikasiJadwal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;


class GuruController extends Controller
{
    // Halaman Ringkasan (Dashboard)
    public function dashboard()
    {
        $guru = \Illuminate\Support\Facades\Auth::user();
        $sekolah = \App\Models\Sekolah::find($guru->id_sekolah);
        $nama_sekolah = $sekolah ? $sekolah->nama_sekolah : 'Sekolah LENTERA';
        $kode_lisensi_siswa = $sekolah ? $sekolah->kode_lisensi_siswa : 'KODE-BELUM-DISET';

        // 2. Hitung Total Siswa di sekolah yang sama
        $userIdsSiswa = \App\Models\User::where('id_role', 3)
            ->where('id_sekolah', $guru->id_sekolah)
            ->pluck('id');

        $total_siswa = $userIdsSiswa->count();
        $siswaIds = \App\Models\Siswa::whereIn('id_user', $userIdsSiswa)->pluck('id_siswa');

        // 3. Hitung Laporan yang sudah selesai
        $laporan_diakses = \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)
            ->where('status', 'selesai')
            ->count();

        // 4. Tarik 5 Aktivitas Eksplorasi Terkini
        $aktivitas_terkini = \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        foreach ($aktivitas_terkini as $aktivitas) {
            $aktivitas->data_siswa = \App\Models\Siswa::find($aktivitas->id_siswa);
            if ($aktivitas->data_siswa) {
                $aktivitas->akun_siswa = \App\Models\User::find($aktivitas->data_siswa->id_user);
            }
        }

        // 5. Dominansi Bidang (Mengambil rumpun_ilmu dari ML)
        $bidang_dominan = 'Belum Ada Data';

        $eksplorasiSelesaiIds = \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)
            ->where('status', 'selesai')
            ->pluck('id_eksplorasi');

        $hasilML = \App\Models\EksplorasiGambar::whereIn('id_eksplorasi', $eksplorasiSelesaiIds)
            ->whereNotNull('hasil_ocr')
            ->pluck('hasil_ocr');

        // C. Ekstrak 'rumpun_ilmu' dari analisis_akademik hasil ML
        $rumpunList = collect($hasilML)->map(function ($json) {
            $data = json_decode($json, true);
            // Ambil dari path: analisis_akademik -> rumpun_ilmu
            return $data['analisis_akademik']['rumpun_ilmu'] ?? null;
        })->filter();

        // D. Cari rumpun yang paling sering muncul (Modus)
        if ($rumpunList->isNotEmpty()) {
            $bidang_dominan = $rumpunList->mode()[0];
        }

        return view('guru.ringkasan', compact(
            'nama_sekolah',
            'kode_lisensi_siswa',
            'total_siswa',
            'laporan_diakses',
            'aktivitas_terkini',
            'bidang_dominan'
        ));
    }

    public function siswa(Request $request)
    {
        $user = Auth::user();

        // 1. Tentukan Default Angkatan (Tahun Sekarang dikurangi 2)
        $tahunSekarang = date('Y'); // Mengambil tahun saat ini (misal: 2026)
        $defaultAngkatan = $tahunSekarang - 2; // Hasil: 2024

        // Ambil filter angkatan dari request, jika tidak ada, gunakan default
        $filterAngkatan = $request->input('filter_angkatan', $defaultAngkatan);

        // 2. Ambil daftar angkatan unik untuk mengisi opsi Dropdown
        $angkatans = \App\Models\Siswa::whereHas('user', function ($q) use ($user) {
            $q->where('id_sekolah', $user->id_sekolah);
        })
            ->select('angkatan')
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        // 3. Query Data Siswa
        $query = User::where('id_sekolah', $user->id_sekolah)
            ->where('id_role', 3);

        // 4. Terapkan Filter Angkatan (jika bukan 'all')
        if ($filterAngkatan != 'all') {
            $query->whereHas('siswa', function ($q) use ($filterAngkatan) {
                $q->where('angkatan', $filterAngkatan);
            });
        }

        // 5. Terapkan Filter Pencarian Nama
        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        $siswas = $query->paginate(10);

        return view('guru.siswa', compact('siswas', 'angkatans', 'filterAngkatan'));
    }

    // Halaman Detail Profil Siswa
    public function detailSiswa($id)
    {
        $guru = Auth::user();

        $siswaUser = User::where('id_role', 3)
            ->where('id_sekolah', $guru->id_sekolah)
            ->findOrFail($id);

        $dataSiswa = Siswa::where('id_user', $siswaUser->id)->first();

        $eksplorasi = null;
        $nilaiAkademik = collect();
        $ml_data = null;

        if ($dataSiswa) {

            $eksplorasi = Eksplorasi::where(
                'id_siswa',
                $dataSiswa->id_siswa
            )
                ->latest()
                ->first();

            if ($eksplorasi) {

                $nilaiAkademik = NilaiAkademik::with('mapel')
                    ->where('id_eksplorasi', $eksplorasi->id_eksplorasi)
                    ->whereNotNull('nilai')
                    ->get();

                if ($eksplorasi->status === 'selesai') {

                    $gambar = EksplorasiGambar::where(
                        'id_eksplorasi',
                        $eksplorasi->id_eksplorasi
                    )
                        ->latest()
                        ->first();

                    if ($gambar && !empty($gambar->hasil_ocr)) {

                        $decoded = json_decode($gambar->hasil_ocr, true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            $ml_data = $decoded;
                        }
                    }
                }
            }
        }

        return view('guru.siswa_detail', compact(
            'siswaUser',
            'dataSiswa',
            'eksplorasi',
            'nilaiAkademik',
            'ml_data'
        ));
    }
    public function dominasiBidang(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // 1. Ambil semua siswa di sekolah guru tersebut
        $query = \App\Models\User::where('id_sekolah', $user->id_sekolah)
            ->where('id_role', 3); // Role Siswa

        // Fitur Pencarian
        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        // Ambil data siswa dengan relasi yang dibutuhkan
        $siswas = $query->paginate(10);

        // 2. Olah data untuk tabel (menambahkan bidang_ai secara dinamis)
        foreach ($siswas as $siswa) {
            $data_siswa = \App\Models\Siswa::where('id_user', $siswa->id)->first();
            if ($data_siswa) {
                $eksplorasi = \App\Models\Eksplorasi::where('id_siswa', $data_siswa->id_siswa)
                    ->where('status', 'selesai')->first();

                if ($eksplorasi) {
                    $gambar = \App\Models\EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
                    $ml_data = $gambar ? json_decode($gambar->hasil_ocr, true) : null;
                    $siswa->bidang_ai = $ml_data['analisis_akademik']['rumpun_ilmu'] ?? 'Tidak Terdeteksi';
                } else {
                    $siswa->bidang_ai = '-';
                }
            }
        }

        // 3. Olah data untuk Diagram Batang (Rekap per sekolah)
        $siswaIds = \App\Models\Siswa::whereIn('id_user', function ($q) use ($user) {
            $q->select('id')->from('users')->where('id_sekolah', $user->id_sekolah);
        })->pluck('id_siswa');

        $hasilML = \App\Models\EksplorasiGambar::whereIn(
            'id_eksplorasi',
            \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)->pluck('id_eksplorasi')
        )->whereNotNull('hasil_ocr')->pluck('hasil_ocr');

        $rekapBidang = [];
        foreach ($hasilML as $json) {
            $data = json_decode($json, true);
            $rumpun = $data['analisis_akademik']['rumpun_ilmu'] ?? 'Lainnya';
            $rekapBidang[$rumpun] = ($rekapBidang[$rumpun] ?? 0) + 1;
        }

        return view('guru.dominasi', compact('siswas', 'rekapBidang'));
    }

    // Halaman Kelengkapan Profil Guru
    public function profil()
    {
        $user = Auth::user();
        // Mengambil data spesifik guru (NIP, dll) dari tabel guru
        $guru = Guru::where('id_user', $user->id)->first();

        return view('guru.profil', compact('user', 'guru'));
    }

    // Fungsi untuk menyimpan perubahan profil guru
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'required|string|max:50',
        ]);

        // Update nama di tabel users
        $user->update(['nama' => $request->nama]);

        // Update NIP di tabel guru
        $guru = Guru::where('id_user', $user->id)->first();
        if ($guru) {
            $guru->update(['nip' => $request->nip]);
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

        // Cek apakah password lama yang dimasukkan sesuai dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan catatan kami.']);
        }

        // Update ke password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui!');
    }
    // ==========================================
    // FITUR SMART TRIAGE: RUANG KONSULTASI GURU
    // ==========================================

    public function konsultasi()
    {
        $user = Auth::user();

        // Cari identitas ID Guru dari user yang sedang login
        $dataGuru = Guru::where('id_user', $user->id)->first();

        $konsultasi = [];

        // Jika data guru ditemukan, tarik pengajuan yang id_guru-nya cocok dengan guru ini
        if ($dataGuru) {
            $konsultasi = \App\Models\Konsultasi::where('id_guru', $dataGuru->id_guru)
                ->orderByRaw("FIELD(status, 'Menunggu', 'Dijadwalkan', 'Selesai')")
                ->orderByRaw("FIELD(tingkat_prioritas, 'Tinggi', 'Menengah', 'Rendah')")
                ->orderBy('created_at', 'desc')
                ->get();

            // Injeksi manual data akun siswa untuk ditampilkan di layar
            foreach ($konsultasi as $k) {
                $k->data_siswa = Siswa::find($k->id_siswa);
                if ($k->data_siswa) {
                    $k->akun_siswa = User::find($k->data_siswa->id_user);
                }
            }
        }

        return view('guru.konsultasi', compact('konsultasi'));
    }

    public function jadwalkanKonsultasi(Request $request, $id)
    {
        $guru = Auth::user();
        $konsultasi = \App\Models\Konsultasi::findOrFail($id);

        // Validasi input tanggal/waktu
        $request->validate([
            'jadwal_konsultasi' => 'required|date'
        ]);

        $dataGuru = Guru::where('id_user', $guru->id)->first();

        // 1. Simpan perubahan status dan jadwal ke database
        $konsultasi->update([
            'status' => 'Dijadwalkan',
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
            'id_guru' => $dataGuru ? $dataGuru->id_guru : null
        ]);

        // 2. Cari Email Siswa dengan Alur Relasi yang Benar
        // Konsultasi -> id_siswa -> Tabel Siswa -> id_user -> Tabel User -> email
        $dataSiswa = \App\Models\Siswa::find($konsultasi->id_siswa);

        if ($dataSiswa) {
            $akunSiswa = User::find($dataSiswa->id_user);

            if ($akunSiswa && $akunSiswa->email) {
                // 3. Eksekusi pengiriman Email & Kalender
                Mail::to($akunSiswa->email)->send(new NotifikasiJadwal($akunSiswa, $konsultasi));
            }
        }

        return back()->with('success', 'Jadwal konsultasi berhasil ditetapkan dan notifikasi kalender telah dikirim ke email siswa!');
    }

    public function selesaikanKonsultasi(Request $request, $id)
    {
        $konsultasi = \App\Models\Konsultasi::findOrFail($id);

        $request->validate(['catatan_guru' => 'required|string']);

        $konsultasi->update([
            'status' => 'Selesai',
            'catatan_guru' => $request->catatan_guru
        ]);

        return back()->with('success', 'Konsultasi selesai! Catatan telah disimpan ke riwayat siswa.');
    }
    public function panduan()
    {
        // Mengarahkan ke file view resources/views/siswa/panduan.blade.php
        return view('guru.panduan');
    }
}
