<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Guru;

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
    public function detailSiswa($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Pastikan guru hanya bisa melihat siswa dari sekolahnya sendiri (Keamanan)
        $siswa = \App\Models\User::with('siswa')
            ->where('id_sekolah', $user->id_sekolah)
            ->findOrFail($id);

        return view('guru.siswa_detail', compact('siswa'));
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
