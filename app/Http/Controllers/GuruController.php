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


class GuruController extends Controller
{
    // Halaman Ringkasan (Dashboard)
    public function dashboard()
    {
        $guru = \Illuminate\Support\Facades\Auth::user();
        $sekolah = \App\Models\Sekolah::find($guru->id_sekolah);
        $nama_sekolah = $sekolah ? $sekolah->nama_sekolah : 'Sekolah LENTERA';
        $kode_lisensi_siswa = $sekolah ? $sekolah->kode_lisensi_siswa : 'KODE-BELUM-DISET';
        // 1. Ambil Nama Sekolah
        $sekolah = \App\Models\Sekolah::find($guru->id_sekolah);
        $nama_sekolah = $sekolah ? $sekolah->nama_sekolah : 'Sekolah LENTERA';

        // 2. Hitung Total Siswa di sekolah yang sama
        $userIdsSiswa = \App\Models\User::where('id_role', 3)
            ->where('id_sekolah', $guru->id_sekolah)
            ->pluck('id');

        $total_siswa = $userIdsSiswa->count();
        $siswaIds = \App\Models\Siswa::whereIn('id_user', $userIdsSiswa)->pluck('id_siswa');

        // 3. Hitung Laporan yang sudah selesai (diakses)
        $laporan_diakses = \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)
            ->where('status', 'selesai')
            ->count();

        // 4. Tarik 5 Aktivitas Eksplorasi Terkini
        $aktivitas_terkini = \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Injeksi manual data akun siswa untuk mempermudah view
        foreach ($aktivitas_terkini as $aktivitas) {
            $aktivitas->data_siswa = \App\Models\Siswa::find($aktivitas->id_siswa);
            if ($aktivitas->data_siswa) {
                $aktivitas->akun_siswa = \App\Models\User::find($aktivitas->data_siswa->id_user);
            }
        }

        // 5. Dominansi Bidang (Membaca langsung dari hasil Machine Learning)
        $bidang_dominan = 'Belum Ada Data';

        // A. Ambil ID Eksplorasi yang sudah selesai untuk siswa di sekolah ini
        $eksplorasiSelesaiIds = \App\Models\Eksplorasi::whereIn('id_siswa', $siswaIds)
            ->where('status', 'selesai')
            ->pluck('id_eksplorasi');

        // B. Ambil JSON hasil_ocr dari tabel gambar
        $hasilML = \App\Models\EksplorasiGambar::whereIn('id_eksplorasi', $eksplorasiSelesaiIds)
            ->whereNotNull('hasil_ocr')
            ->pluck('hasil_ocr');

        // C. Ekstrak rekomendasi jurusan pertama dari setiap JSON siswa
        $jurusanList = collect($hasilML)->map(function ($json) {
            $data = json_decode($json, true);
            // Ambil nama jurusan yang ada di ranking 1 (index 0)
            return $data['rekomendasi_jurusan'][0]['jurusan'] ?? null;
        })->filter(); // filter() berguna untuk membuang hasil yang null/kosong

        // D. Cari jurusan yang paling sering muncul (Modus)
        if ($jurusanList->isNotEmpty()) {
            // Fungsi mode() bawaan Laravel otomatis mencari data terbanyak
            $jurusan_terbanyak = $jurusanList->mode()[0];

            // Kita gunakan nama jurusan tersebut sebagai Bidang Dominan
            $bidang_dominan = $jurusan_terbanyak;
        }
        return view('guru.ringkasan', compact('nama_sekolah', 'kode_lisensi_siswa', 'total_siswa', 'laporan_diakses', 'aktivitas_terkini', 'bidang_dominan'));
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

        // 1. Ambil data akun User (Pastikan dia siswa dan satu sekolah dengan guru)
        $siswaUser = User::where('id_role', 3)
            ->where('id_sekolah', $guru->id_sekolah)
            ->findOrFail($id);

        // 2. Cari data diri spesifik di tabel Siswa
        $dataSiswa = Siswa::where('id_user', $siswaUser->id)->first();

        // 3. Siapkan variabel default agar view kebal dari error (jika siswa belum tes)
        $eksplorasi = null;
        $nilaiAkademik = [];
        $ml_data = null;

        if ($dataSiswa) {
            // Ambil data eksplorasi (tes AI) terbaru milik siswa ini
            $eksplorasi = Eksplorasi::where('id_siswa', $dataSiswa->id_siswa)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($eksplorasi) {
                // Tarik riwayat nilai mata pelajaran
                $nilaiAkademik = NilaiAkademik::with('mapel')
                    ->where('id_eksplorasi', $eksplorasi->id_eksplorasi)
                    ->whereNotNull('nilai')
                    ->get();

                // Tarik hasil Machine Learning jika statusnya sudah selesai (Finalisasi)
                if ($eksplorasi->status == 'selesai') {
                    $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
                    if ($gambar && $gambar->hasil_ocr) {
                        // Ubah teks JSON dari database menjadi Array PHP
                        $ml_data = json_decode($gambar->hasil_ocr, true);
                    }
                }
            }
        }

        // 4. Kirim semua data tersebut ke file guru/siswa_detail.blade.php
        return view('guru.siswa_detail', compact('siswaUser', 'dataSiswa', 'eksplorasi', 'nilaiAkademik', 'ml_data'));
    }
    public function dominasiBidang(Request $request)
    {
        $user = Auth::user();

        // 1. DATA UNTUK DIAGRAM BATANG (rekap jumlah siswa per bidang)
        $rekapBidang = $this->hitungRekapBidang($user->id_sekolah);

        // 2. DATA UNTUK TABEL SISWA (Lebih aman dari error relation)
        $query = User::where('id_sekolah', $user->id_sekolah)
            ->where('id_role', 3); // 3 = ID Role Siswa

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        $siswas = $query->paginate(10);

        // 3. Ekstrak data bidang untuk masing-masing siswa yang tampil di halaman ini
        foreach ($siswas as $siswaUser) {
            $dataSiswa = Siswa::where('id_user', $siswaUser->id)->first();
            $siswaUser->siswa_data = $dataSiswa; // Tempelkan data detail siswa
            $siswaUser->bidang_ai = '-'; // Default jika belum ada

            if ($dataSiswa) {
                $eksplorasi = Eksplorasi::where('id_siswa', $dataSiswa->id_siswa)
                    ->where('status', 'selesai')
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($eksplorasi) {
                    $gambar = EksplorasiGambar::where('id_eksplorasi', $eksplorasi->id_eksplorasi)->first();
                    if ($gambar && $gambar->hasil_ocr) {
                        $ml_data = json_decode($gambar->hasil_ocr, true);
                        // Ambil kategori bidang dari rank 1 atau dari field karakter (sesuaikan JSON ML Anda)
                        $siswaUser->bidang_ai = $ml_data['karakter']['tipe'] ??
                            ($ml_data['rekomendasi_jurusan'][0]['jurusan'] ?? 'Tidak Diketahui');
                    }
                }
            }
        }

        return view('guru.dominasi', compact('rekapBidang', 'siswas'));
    }

    private function hitungRekapBidang($id_sekolah)
    {
        // Gunakan pencarian role langsung lewat id_role
        $idUserSiswaSekolah = User::where('id_sekolah', $id_sekolah)
            ->where('id_role', 3)
            ->pluck('id');

        $idSiswaSekolah = Siswa::whereIn('id_user', $idUserSiswaSekolah)->pluck('id_siswa');

        $eksplorasiSelesai = Eksplorasi::whereIn('id_siswa', $idSiswaSekolah)
            ->where('status', 'selesai')
            ->get();

        $rekapBidang = [];

        foreach ($eksplorasiSelesai as $e) {
            $gambar = EksplorasiGambar::where('id_eksplorasi', $e->id_eksplorasi)->first();
            if (!$gambar || !$gambar->hasil_ocr) {
                continue;
            }

            $hasil = json_decode($gambar->hasil_ocr, true);

            // Mencari bidang dari struktur JSON yang sudah disepakati (karakter tipe atau jurusan teratas)
            $bidang = $hasil['karakter']['tipe'] ??
                ($hasil['rekomendasi_jurusan'][0]['jurusan'] ?? null);

            if ($bidang) {
                // Untuk mencegah nama jurusan kepanjangan, kita bisa mengkategorikannya
                $rekapBidang[$bidang] = ($rekapBidang[$bidang] ?? 0) + 1;
            }
        }

        arsort($rekapBidang); // urutkan dari yang paling banyak muncul
        return $rekapBidang;
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
}
