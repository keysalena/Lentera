<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\Guru;

class AuthController extends Controller
{
    public function index()
    {
        // Jika user sudah login, arahkan ke dashboard sesuai role
        if (Auth::check()) {
            // Menggunakan optional helper untuk menghindari error jika relasi role null
            $role = optional(Auth::user()->role)->nama_role;
            return $role ? redirect()->route($role . '.dashboard') : redirect('/');
        }

        return view('auth'); // Pastikan file resources/views/auth.blade.php ada
    }

    public function showRegister()
    {
        $sekolahs = Sekolah::all();
        return view('register', compact('sekolahs'));
    }

    public function register(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Validasi Khusus Guru (Wajib Kode Lisensi & Harus Cocok)
        if ($request->role === 'guru') {
            $request->validate([
                'kode_lisensi' => 'required|string',
            ], [
                'kode_lisensi.required' => 'Kode Lisensi wajib diisi untuk pendaftaran Guru BK.'
            ]);

            $sekolah = Sekolah::find($request->id_sekolah);

            if ($sekolah->kode_lisensi !== $request->kode_lisensi) {
                return back()->withErrors([
                    'kode_lisensi' => 'Kode Lisensi yang Anda masukkan tidak valid untuk instansi ini.'
                ])->withInput(); // withInput mengembalikan data yang sudah diketik agar tidak perlu mengisi ulang
            }
        }
        if ($request->role === 'siswa') {
            $request->validate([
                'angkatan' => 'required|numeric|digits:4',
            ]);
        }
        // 3. Tentukan ID Role (Guru = 2, Siswa = 3)
        $id_role = $request->role === 'guru' ? 2 : 3;

        // 4. Simpan ke tabel users
        $user = User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $id_role,
            'id_sekolah' => $request->id_sekolah,
        ]);

        // 5. Buat profil default di tabel guru atau siswa
        if ($id_role === 3) {
            Siswa::create([
                'id_user' => $user->id,
                'nisn' => 'S' . time(),
                'jenis_kelamin' => 'L',
                'angkatan' => $request->angkatan, 
            ]);
        } else {
            Guru::create([
                'id_user' => $user->id,
                'nip' => 'G' . time(),
            ]);
        }

        // 6. Login otomatis & Arahkan ke dashboard
        Auth::login($user);

        return redirect()->route($request->role . '.dashboard')->with('success', 'Akun berhasil dibuat!');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Mengambil nama role dari relasi
            $role = optional($user->role)->nama_role;

            if ($role) {
                return redirect()->route($role . '.dashboard');
            }

            // Jika role tidak ditemukan
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak memiliki peran yang terdaftar.']);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah keluar.');
    }
}
