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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AuthController extends Controller
{
    public function index()
    {
        
        if (Auth::check()) {
            $role = optional(Auth::user()->role)->nama_role;
            return $role ? redirect()->route($role . '.dashboard') : redirect('/');
        }

        return view('auth');
    }

    public function showRegister()
    {
        
        $sekolahs = Sekolah::all();
        return view('register', compact('sekolahs'));
    }

    public function register(Request $request)
    {
        
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $id_sekolah = null; 

        
        if ($request->role === 'guru') {
            $request->validate([
                'id_sekolah' => 'required|exists:sekolahs,id_sekolah', 
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

        
        if ($request->role === 'siswa') {
            $request->validate([
                'angkatan' => 'required|numeric|digits:4',
                'kode_lisensi_siswa' => 'nullable|string', 
            ]);

            
            if ($request->filled('kode_lisensi_siswa')) {
                
                $sekolah = Sekolah::where('kode_lisensi_siswa', $request->kode_lisensi_siswa)->first();

                if (!$sekolah) {
                    return back()->withErrors([
                        'kode_lisensi_siswa' => 'Kode Registrasi Sekolah tidak ditemukan atau tidak valid.'
                    ])->withInput();
                }

                
                $id_sekolah = $sekolah->id_sekolah ?? $sekolah->id;
            }
        }

        
        $id_role = $request->role === 'guru' ? 2 : 3;

        
        $user = User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $id_role,
            'id_sekolah' => $id_sekolah, 
        ]);

        
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
    public function showResetPassword()
    {
        return view('forget');
    }
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        
        $token = Str::random(64);

        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token), 
                'created_at' => Carbon::now()
            ]
        );

        
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        
        try {
            Mail::raw("Halo {$user->nama},\n\nKami menerima permintaan untuk mereset kata sandi Anda. Silakan klik tautan di bawah ini untuk membuat kata sandi baru:\n\n{$resetUrl}\n\nTautan ini hanya berlaku selama 60 menit.\n\nJika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Permintaan Reset Kata Sandi LENTERA');
            });

            return back()->with('success', 'Tautan reset kata sandi telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti.']);
        }
    }

    
    public function showResetForm(Request $request, $token)
    {
        
        return view('reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        
        $tokenData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        
        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Token reset kata sandi tidak valid.']);
        }

        
        if (Carbon::parse($tokenData->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Tautan reset telah kedaluwarsa. Silakan minta tautan baru.']);
        }

        
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        
        return redirect()->route('login')->with('success', 'Kata sandi Anda berhasil diperbarui. Silakan masuk.');
    }
}
