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
            $role = optional(Auth::user()->role)->nama_role;
            return $role ? redirect()->route($role . '.dashboard') : redirect('/');
        }

        return view('auth');
    }

    public function showRegister()
    {
        // Tetap memanggil data sekolah untuk dropdown form Guru BK
        $sekolahs = Sekolah::all();
        return view('register', compact('sekolahs'));
    }

    public function register(Request $request)
    {
        // 1. Validasi Input Dasar (Berlaku untuk Guru & Siswa)
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $id_sekolah = null; // Default: Null untuk antisipasi Siswa Mandiri

        // 2. Validasi & Eksekusi Khusus Guru BK (Wajib Sekolah & Kode Lisensi Guru)
        if ($request->role === 'guru') {
            $request->validate([
                'id_sekolah' => 'required|exists:sekolahs,id_sekolah', // Pastikan nama tabel database Anda 'sekolahs' atau 'sekolah'
                'kode_lisensi' => 'required|string',
            ], [
                'id_sekolah.required' => 'Pilih instansi sekolah untuk pendaftaran Guru BK.',
                'kode_lisensi.required' => 'Kode Lisensi wajib diisi untuk pendaftaran Guru BK.'
            ]);

            $sekolah = Sekolah::find($request->id_sekolah);

            if (!$sekolah || $sekolah->kode_lisensi !== $request->kode_lisensi) {
                return back()->withErrors([
                    'kode_lisensi' => 'Kode Lisensi Guru yang Anda masukkan tidak valid untuk instansi ini.'
                ])->withInput();
            }

            $id_sekolah = $request->id_sekolah;
        }

        // 3. Validasi & Eksekusi Khusus Siswa (Lisensi Opsional)
        if ($request->role === 'siswa') {
            $request->validate([
                'angkatan' => 'required|numeric|digits:4',
                'kode_lisensi_siswa' => 'nullable|string', // Opsional
            ]);

            // Jika siswa memasukkan kode lisensi di form untuk membuka fitur konseling
            if ($request->filled('kode_lisensi_siswa')) {
                // Cari sekolah yang memiliki kode lisensi siswa tersebut
                $sekolah = Sekolah::where('kode_lisensi_siswa', $request->kode_lisensi_siswa)->first();

                if (!$sekolah) {
                    return back()->withErrors([
                        'kode_lisensi_siswa' => 'Kode Registrasi Sekolah tidak ditemukan atau tidak valid.'
                    ])->withInput();
                }

                // Jika valid, hubungkan siswa dengan sekolah tersebut
                $id_sekolah = $sekolah->id_sekolah ?? $sekolah->id; 
            }
        }

        // 4. Tentukan ID Role
        $id_role = $request->role === 'guru' ? 2 : 3;

        // 5. Simpan ke tabel users
        $user = User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $id_role,
            'id_sekolah' => $id_sekolah, // Berisi ID untuk Guru & Siswa Terdaftar, Null untuk Siswa Mandiri
        ]);

        // 6. Buat profil default di tabel guru atau siswa
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

        // 7. Login otomatis & Arahkan ke dashboard
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
            $role = optional($user->role)->nama_role;

            if ($role) {
                return redirect()->route($role . '.dashboard');
            }

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