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
        
        $sekolahs = \App\Models\Sekolah::orderBy('nama_sekolah', 'asc')->get();

        
        $angkatans = \App\Models\Siswa::whereNotNull('angkatan')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        
        $query = \App\Models\User::with(['sekolah', 'siswa'])
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'siswa');
            });

        
        if ($request->has('filter_sekolah') && $request->filter_sekolah != 'all') {
            $query->where('id_sekolah', $request->filter_sekolah);
        }

        if ($request->has('filter_angkatan') && $request->filter_angkatan != 'all') {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('angkatan', $request->filter_angkatan);
            });
        }

        if ($request->has('cari') && $request->cari != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                    ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

        $siswas = $query->paginate(10);

        return view('admin.siswa', compact('siswas', 'sekolahs', 'angkatans'));
    }

    public function guru(\Illuminate\Http\Request $request)
    {
        $sekolahs = \App\Models\Sekolah::orderBy('nama_sekolah', 'asc')->get();

        $query = \App\Models\User::with(['sekolah.users.role', 'guru'])
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'guru');
            });

        if ($request->has('filter_sekolah') && $request->filter_sekolah != 'all') {
            $query->where('id_sekolah', $request->filter_sekolah);
        }

        if ($request->has('cari') && $request->cari != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                    ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

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
            'kode_lisensi_siswa' => $kodeLisensiSiswa,
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

        $sekolah->update([
            'nama_sekolah' => $request->nama_sekolah,
            'alamat' => $request->alamat,
        ]);

        if ($request->has('reset_lisensi')) {
            $sekolah->regenerateLicenses();
            return redirect()->back()->with('success', 'Data dan Kode Lisensi berhasil diperbarui/di-reset!');
        }

        return redirect()->back()->with('success', 'Data sekolah berhasil diperbarui!');
    }

    public function destroySekolah($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $sekolah->delete();

        return redirect()->back()->with('success', 'Sekolah berhasil dihapus dari sistem!');
    }
    public function detailSekolah($id)
    {
        $sekolah = \App\Models\Sekolah::with(['users.role'])->findOrFail($id);

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
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => 2,
                'id_sekolah' => $request->id_sekolah,
            ]);

            Guru::create([
                'id_user' => $user->id,
                'nip' => $request->nip,
            ]);
        });

        return redirect()->back()->with('success', 'Akun Guru BK berhasil ditambahkan!');
    }

    public function updateGuru(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $guru = Guru::where('id_user', $id)->first();

        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'nip' => 'required|string|max:50|unique:guru,nip,' . ($guru ? $guru->id_guru : 'NULL') . ',id_guru',
            'password' => 'nullable|string|min:8', 
        ]);

        DB::transaction(function () use ($request, $user, $guru) {
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'id_sekolah' => $request->id_sekolah,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($guru) {
                $guru->update(['nip' => $request->nip]);
            }
        });

        return redirect()->back()->with('success', 'Data Guru BK berhasil diperbarui!');
    }

    public function destroyGuru($id)
    {
        $user = User::findOrFail($id);

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
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => 3,
                'id_sekolah' => $request->id_sekolah,
            ]);

            Siswa::create([
                'id_user' => $user->id,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'angkatan' => $request->angkatan,
            ]);
        });

        return redirect()->back()->with('success', 'Akun Siswa berhasil ditambahkan!');
    }

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
            'password' => 'nullable|string|min:8', 
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

    public function destroySiswa($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); 

        return redirect()->back()->with('success', 'Akun Siswa berhasil dihapus dari sistem!');
    }
    public function mapel(\Illuminate\Http\Request $request)
    {
        $query = MataPelajaran::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_mapel', 'like', '%' . $request->cari . '%');
        }

        $mapels = $query->orderBy('nama_mapel', 'asc')->paginate(10);

        return view('admin.mapel', compact('mapels'));
    }

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

    public function destroyMapel($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $mapel->delete();

        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus dari sistem!');
    }
    public function kemampuan(\Illuminate\Http\Request $request)
    {
        $query = Kemampuan::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_kemampuan', 'like', '%' . $request->cari . '%')
                ->orWhere('kode_item', 'like', '%' . $request->cari . '%');
        }

        $kemampuans = $query->orderBy('kode_item', 'asc')->paginate(10);

        return view('admin.kemampuan', compact('kemampuans'));
    }

    public function storeKemampuan(\Illuminate\Http\Request $request)
    {
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

    public function updateKemampuan(\Illuminate\Http\Request $request, $id)
    {
        $kemampuan = Kemampuan::findOrFail($id);

        $cleanKode = str_replace('q_', '', $request->kode_item);
        $fKode = "q_" . $cleanKode;

        $request->validate([
            'kode_item' => 'required|string|max:10|unique:kemampuan,kode_item,' . $id . ',id_kemampuan',
            'nama_kemampuan' => 'required|string',
        ]);

        $kemampuan->update([
            'kode_item' => $fKode,
            'nama_kemampuan' => $request->nama_kemampuan,
        ]);

        return redirect()->back()->with('success', 'Berhasil diperbarui!');
    }

    public function destroyKemampuan($id)
    {
        $kemampuan = Kemampuan::findOrFail($id);
        $kemampuan->delete();

        return redirect()->back()->with('success', 'Indikator kemampuan berhasil dihapus dari sistem!');
    }
}
