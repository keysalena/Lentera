<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'LENTERA — Masuk')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/css/style.css?v={{ time() }}">
</head>

<body>

    <div style="background: var(--cream); min-height: calc(100vh - 72px); display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
        <div class="auth-container" style="background: var(--white); width: 100%; max-width: 480px; padding: 40px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 12px 32px rgba(87, 94, 112, 0.06);">

            <div style="text-align: center; margin-bottom: 24px;">
                <div style="width: 48px; height: 48px; background: var(--amber); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                        <path d="M10 2L12.5 7.5L18 8.5L14 12.5L15 18L10 15.5L5 18L6 12.5L2 8.5L7.5 7.5L10 2Z" fill="white" />
                    </svg>
                </div>
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink); margin-bottom: 8px;">Selamat Datang</h2>
                <p style="font-size: 14px; color: var(--ink-60);">Masuk atau buat akun baru untuk memulai analisis</p>
            </div>

            @if(session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; margin-bottom: 24px; text-align: center; border: 1px solid #A7F3D0;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #FEE2E2; color: #B91C1C; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; margin-bottom: 24px; text-align: center; border: 1px solid #FECACA;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #FEE2E2; color: #B91C1C; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 500; margin-bottom: 24px; border: 1px solid #FECACA;">
                    <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                <div>
                    <label for="email" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@sekolah.sch.id"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); color: var(--ink); outline: none; transition: border-color 0.2s;">
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="password" style="font-size: 13px; font-weight: 600; color: var(--ink);">Kata Sandi</label>
                        <a href="#" style="font-size: 12px; color: var(--amber); text-decoration: none; font-weight: 500;">Lupa Sandi?</a>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); color: var(--ink); outline: none; transition: border-color 0.2s;">
                </div>

                <div style="margin-top: 8px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px; font-weight: 700; cursor: pointer; border: none; background: var(--amber); color: white;">
                        Masuk ke Platform
                    </button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--cream);">
                <p style="font-size: 14px; color: var(--ink-60);">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" style="color: var(--amber); font-weight: 700; text-decoration: none;">Daftar Sekarang</a>
                </p>
            </div>

        </div>
    </div>

</body>

</html>