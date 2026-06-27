<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Eksplorasi;
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

        // ── HITUNG BIDANG PALING DOMINAN UNTUK CARD RINGKASAN ──
        $bidang_dominan = 'Belum Ada Data';
        $rekapBidangSekolah = $this->hitungRekapBidang($user->id_sekolah);

        if (!empty($rekapBidangSekolah)) {
            $bidang_dominan = array_key_first($rekapBidangSekolah);
        }

        return view('guru.ringkasan', compact('total_siswa', 'nama_sekolah', 'aktivitas_terkini', 'bidang_dominan'));
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
    public function detailSiswa($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Pastikan guru hanya bisa melihat siswa dari sekolahnya sendiri (Keamanan)
        $siswa = \App\Models\User::with('siswa')
            ->where('id_sekolah', $user->id_sekolah)
            ->findOrFail($id);

        return view('guru.siswa_detail', compact('siswa'));
    }

    // ═════════════════════════════════════════════════════════════════
    // HALAMAN BARU: DOMINASI BIDANG
    // Dibuka dari card "Dominansi Bidang" di Ringkasan. Halaman ini berisi:
    //   1. Diagram batang horizontal jumlah siswa per bidang rekomendasi.
    //   2. Di bawahnya, daftar siswa (Nama, Tahun Masuk, Status, Tindakan)
    //      dengan format & tampilan SAMA seperti halaman Daftar Siswa asli
    //      (guru.siswa), supaya konsisten dan tetap bisa cari + paginasi.
    // ═════════════════════════════════════════════════════════════════
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

    // ── FUNGSI BANTUAN: hitung jumlah siswa per bidang rekomendasi ──
    // Dipakai bersama oleh dashboard() dan dominasiBidang() supaya logikanya
    // tidak ditulis dua kali. Query manual pakai kolom id_user / id_siswa
    // (bukan whereHas relasi) agar tidak salah tebak nama foreign key.
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
}