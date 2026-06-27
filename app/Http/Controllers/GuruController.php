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

class GuruController extends Controller
{
    // Halaman Ringkasan (Dashboard)
    public function dashboard()
    {
        $user = Auth::user();

        // Ambil data sekolah dari relasi user
        $sekolah = $user->sekolah;
        $nama_sekolah = $sekolah ? $sekolah->nama_sekolah : 'Sekolah Anda';

        // Hitung total siswa yang mendaftar dari sekolah yang sama dengan guru ini
        $total_siswa = User::where('id_sekolah', $user->id_sekolah)
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'siswa');
            })->count();

        $aktivitas_terkini = [];

        return view('guru.ringkasan', compact('total_siswa', 'nama_sekolah', 'aktivitas_terkini'));
    }

    // Halaman Daftar Siswa
    // Halaman Daftar Siswa (Dengan Search & Pagination)
    public function siswa(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Mulai query mengambil siswa di sekolah yang sama
        $query = \App\Models\User::with('siswa') // Ambil relasi profil siswa
            ->where('id_sekolah', $user->id_sekolah)
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'siswa');
            });

        // Fitur Pencarian
        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        // Ambil data dengan paginasi (10 data per halaman)
        $siswas = $query->paginate(10);

        return view('guru.siswa', compact('siswas'));
    }

    // Halaman Detail Profil Siswa
    // Halaman Detail Siswa (Diakses oleh Guru)
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

        // 2. DATA UNTUK TABEL SISWA (format & query SAMA seperti method siswa())
        $query = User::with('siswa')
            ->where('id_sekolah', $user->id_sekolah)
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'siswa');
            });

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        $siswas = $query->paginate(10);

        return view('guru.dominasi', compact('rekapBidang', 'siswas'));
    }

    private function hitungRekapBidang($id_sekolah)
    {
        $idUserSiswaSekolah = User::where('id_sekolah', $id_sekolah)
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'siswa');
            })->pluck('id');

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
            $bidang = $hasil['rekomendasi_bidang'] ?? $hasil['bidang'] ?? null;

            if ($bidang) {
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

        $request->validate(['jadwal_konsultasi' => 'required|date']);

        $dataGuru = Guru::where('id_user', $guru->id)->first();

        $konsultasi->update([
            'status' => 'Dijadwalkan',
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
            'id_guru' => $dataGuru ? $dataGuru->id_guru : null
        ]);

        return back()->with('success', 'Jadwal konsultasi berhasil dikirim ke siswa!');
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
