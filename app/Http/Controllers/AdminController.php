<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\MataPelajaran;
use App\Models\Kemampuan;
use App\Models\Eksplorasi;

class AdminController extends Controller
{
    public function dashboard()
    {
        $total_sekolah = Sekolah::count();
        $total_guru = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'guru');
        })->count();

        $total_siswa = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'siswa');
        })->count();

        // Asumsi: 'selesai' adalah status jika analisis sudah finalisasi
        $total_analisis = Eksplorasi::where('status', 'selesai')->count();

        $sekolahs = Sekolah::with('users.role')->get();

        return view('admin.ringkasan', compact(
            'total_sekolah',
            'total_guru',
            'total_siswa',
            'total_analisis',
            'sekolahs'
        ));
    }

    public function siswa(\Illuminate\Http\Request $request)
    {
        // 1. Ambil data untuk opsi dropdown filter
        $sekolahs = \App\Models\Sekolah::orderBy('nama_sekolah', 'asc')->get();

        // Ambil daftar angkatan unik dari tabel siswa yang tidak kosong
        $angkatans = \App\Models\Siswa::whereNotNull('angkatan')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        // 2. Mulai query ambil data user dengan role siswa (id_role = 3)
        $query = \App\Models\User::with(['sekolah', 'siswa'])
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'siswa');
            });

        // 3. Terapkan Filter Sekolah
        if ($request->has('filter_sekolah') && $request->filter_sekolah != 'all') {
            $query->where('id_sekolah', $request->filter_sekolah);
        }

        // 4. Terapkan Filter Angkatan
        if ($request->has('filter_angkatan') && $request->filter_angkatan != 'all') {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('angkatan', $request->filter_angkatan);
            });
        }

        // 5. Terapkan Pencarian Nama/Email
        if ($request->has('cari') && $request->cari != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                    ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

        // 6. Ambil hasil dengan paginasi (10 per halaman)
        $siswas = $query->paginate(10);

        return view('admin.siswa', compact('siswas', 'sekolahs', 'angkatans'));
    }

    public function guru(\Illuminate\Http\Request $request)
    {
        // Ambil semua data sekolah untuk dropdown filter
        $sekolahs = \App\Models\Sekolah::orderBy('nama_sekolah', 'asc')->get();

        // Query dasar: Ambil user yang rolenya 'guru'
        $query = \App\Models\User::with(['sekolah.users.role', 'guru'])
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'guru');
            });

        // Fitur Filter berdasarkan Sekolah
        if ($request->has('filter_sekolah') && $request->filter_sekolah != 'all') {
            $query->where('id_sekolah', $request->filter_sekolah);
        }

        // Fitur Pencarian berdasarkan Nama atau Email
        if ($request->has('cari') && $request->cari != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                    ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

        // Ambil data dengan paginasi (10 data per halaman)
        $gurus = $query->paginate(10);

        return view('admin.guru', compact('gurus', 'sekolahs'));
    }

    public function sekolah()
    {
        $sekolahs = Sekolah::all();
        return view('admin.sekolah', compact('sekolahs'));
    }

    public function storeSekolah(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:150',
            'alamat' => 'required|string',
        ]);

        $kodeLisensiGuru = 'G-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3));

        $kodeLisensiSiswa = 'S-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3));

        Sekolah::create([
            'nama_sekolah' => $request->nama_sekolah,
            'alamat' => $request->alamat,
            'kode_lisensi' => $kodeLisensiGuru,
            'kode_lisensi_siswa' => $kodeLisensiSiswa, // Simpan ke kolom baru
        ]);

        return redirect()->back()->with('success', 'Sekolah berhasil ditambahkan! Kode Guru: ' . $kodeLisensiGuru . ', Kode Siswa: ' . $kodeLisensiSiswa);
    }

    public function updateSekolah(Request $request, $id)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:150',
            'alamat' => 'required|string',
        ]);

        $sekolah = Sekolah::findOrFail($id);

        // Update data umum
        $sekolah->update([
            'nama_sekolah' => $request->nama_sekolah,
            'alamat' => $request->alamat,
        ]);

        // Jika checkbox "Reset Kode" dicentang, panggil helper model
        if ($request->has('reset_lisensi')) {
            $sekolah->regenerateLicenses();
            return redirect()->back()->with('success', 'Data dan Kode Lisensi berhasil diperbarui/di-reset!');
        }

        return redirect()->back()->with('success', 'Data sekolah berhasil diperbarui!');
    }

    // Fungsi Delete / Hapus
    public function destroySekolah($id)
    {
        $sekolah = Sekolah::findOrFail($id);

        // Opsional: Anda bisa tambahkan logika mengecek apakah masih ada siswa/guru di sekolah ini
        // Jika aman, maka hapus:
        $sekolah->delete();

        return redirect()->back()->with('success', 'Sekolah berhasil dihapus dari sistem!');
    }
    public function detailSekolah($id)
    {
        // Ambil data sekolah beserta user-usernya
        $sekolah = \App\Models\Sekolah::with(['users.role'])->findOrFail($id);

        // Pisahkan user menjadi guru dan siswa untuk mempermudah di view
        $gurus = $sekolah->users->where('role.nama_role', 'guru');
        $siswas = $sekolah->users->where('role.nama_role', 'siswa');

        return view('admin.sekolah_detail', compact('sekolah', 'gurus', 'siswas'));
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'nip' => 'required|string|max:50|unique:guru,nip',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan ke tabel users (role guru = 2)
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => 2,
                'id_sekolah' => $request->id_sekolah,
            ]);

            // 2. Simpan NIP ke tabel guru
            Guru::create([
                'id_user' => $user->id,
                'nip' => $request->nip,
            ]);
        });

        return redirect()->back()->with('success', 'Akun Guru BK berhasil ditambahkan!');
    }

    // Fungsi Update / Edit Data Guru BK
    public function updateGuru(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $guru = Guru::where('id_user', $id)->first();

        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'nip' => 'required|string|max:50|unique:guru,nip,' . ($guru ? $guru->id_guru : 'NULL') . ',id_guru',
            'password' => 'nullable|string|min:8', // Password opsional saat edit
        ]);

        DB::transaction(function () use ($request, $user, $guru) {
            // Update data user dasar
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'id_sekolah' => $request->id_sekolah,
            ];

            // Jika password diisi, ikut diupdate
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update NIP di tabel guru
            if ($guru) {
                $guru->update(['nip' => $request->nip]);
            }
        });

        return redirect()->back()->with('success', 'Data Guru BK berhasil diperbarui!');
    }

    // Fungsi Hapus Akun Guru BK (Soft Delete)
    public function destroyGuru($id)
    {
        $user = User::findOrFail($id);

        // Hapus user (jika model User menggunakan SoftDeletes, data akan aman di DB)
        $user->delete();

        return redirect()->back()->with('success', 'Akun Guru BK berhasil dihapus dari sistem!');
    }
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'nisn' => 'required|string|max:50|unique:siswa,nisn',
            'jenis_kelamin' => 'required|in:L,P',
            'angkatan' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User baru (Role Siswa = 3)
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => 3,
                'id_sekolah' => $request->id_sekolah,
            ]);

            // 2. Buat profil pelengkap di tabel siswa
            Siswa::create([
                'id_user' => $user->id,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'angkatan' => $request->angkatan,
            ]);
        });

        return redirect()->back()->with('success', 'Akun Siswa berhasil ditambahkan!');
    }

    // Fungsi Update / Edit Data Siswa
    public function updateSiswa(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $siswa = Siswa::where('id_user', $id)->first();

        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'nisn' => 'required|string|max:50|unique:siswa,nisn,' . ($siswa ? $siswa->id_siswa : 'NULL') . ',id_siswa',
            'jenis_kelamin' => 'required|in:L,P',
            'angkatan' => 'required|string|max:20',
            'password' => 'nullable|string|min:8', // Password opsional saat edit
        ]);

        DB::transaction(function () use ($request, $user, $siswa) {
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'id_sekolah' => $request->id_sekolah,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($siswa) {
                $siswa->update([
                    'nisn' => $request->nisn,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'angkatan' => $request->angkatan,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Data Siswa berhasil diperbarui!');
    }

    // Fungsi Hapus Akun Siswa (Soft Delete)
    public function destroySiswa($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Menggunakan soft delete jika trait aktif pada model User

        return redirect()->back()->with('success', 'Akun Siswa berhasil dihapus dari sistem!');
    }
    public function mapel(\Illuminate\Http\Request $request)
    {
        $query = MataPelajaran::query();

        // Fitur Pencarian
        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_mapel', 'like', '%' . $request->cari . '%');
        }

        $mapels = $query->orderBy('nama_mapel', 'asc')->paginate(10);

        return view('admin.mapel', compact('mapels'));
    }

    // Fungsi Tambah Mapel
    public function storeMapel(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mata_pelajaran,nama_mapel',
        ], [
            'nama_mapel.unique' => 'Mata pelajaran ini sudah ada di database.'
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    // Fungsi Update Mapel
    public function updateMapel(\Illuminate\Http\Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mata_pelajaran,nama_mapel,' . $id . ',id_mapel',
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->back()->with('success', 'Nama mata pelajaran berhasil diperbarui!');
    }

    // Fungsi Hapus Mapel
    public function destroyMapel($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        // Hapus mapel (Data nilai terkait mungkin akan ikut terhapus jika di database diset CASCADE)
        $mapel->delete();

        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus dari sistem!');
    }
    public function kemampuan(\Illuminate\Http\Request $request)
    {
        $query = Kemampuan::query();

        // Fitur Pencarian (bisa cari berdasarkan kode_item atau nama_kemampuan)
        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_kemampuan', 'like', '%' . $request->cari . '%')
                ->orWhere('kode_item', 'like', '%' . $request->cari . '%');
        }

        // Diurutkan berdasarkan kode_item agar tampilan lebih terstruktur
        $kemampuans = $query->orderBy('kode_item', 'asc')->paginate(10);

        return view('admin.kemampuan', compact('kemampuans'));
    }

    // Fungsi Tambah Indikator Kemampuan
    // Fungsi Tambah Indikator Kemampuan
    public function storeKemampuan(\Illuminate\Http\Request $request)
    {
        // Pastikan kode yang masuk dari input tidak ganda 'q_'
        $cleanKode = str_replace('q_', '', $request->kode_item);
        $fKode = "q_" . $cleanKode;

        $request->validate([
            'kode_item' => 'required|string|max:10|unique:kemampuan,kode_item',
            'nama_kemampuan' => 'required|string',
        ], [
            'kode_item.unique' => 'Kode item ini sudah terdaftar.',
        ]);

        Kemampuan::create([
            'kode_item' => $fKode,
            'nama_kemampuan' => $request->nama_kemampuan,
        ]);

        return redirect()->back()->with('success', 'Berhasil ditambahkan!');
    }

    // Fungsi Update Indikator Kemampuan
    public function updateKemampuan(\Illuminate\Http\Request $request, $id)
    {
        $kemampuan = Kemampuan::findOrFail($id);

        $cleanKode = str_replace('q_', '', $request->kode_item);
        $fKode = "q_" . $cleanKode;

        $request->validate([
            // Validasi unique mengecualikan ID saat ini
            'kode_item' => 'required|string|max:10|unique:kemampuan,kode_item,' . $id . ',id_kemampuan',
            'nama_kemampuan' => 'required|string',
        ]);

        $kemampuan->update([
            'kode_item' => $fKode,
            'nama_kemampuan' => $request->nama_kemampuan,
        ]);

        return redirect()->back()->with('success', 'Berhasil diperbarui!');
    }

    // Fungsi Hapus Indikator Kemampuan
    public function destroyKemampuan($id)
    {
        $kemampuan = Kemampuan::findOrFail($id);
        $kemampuan->delete();

        return redirect()->back()->with('success', 'Indikator kemampuan berhasil dihapus dari sistem!');
    }
}
