<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>LENTERA — Atur Ulang Kata Sandi</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/css/style.css?v={{ time() }}">
</head>

<body>

    <div style="background: var(--cream); min-height: calc(100vh - 72px); display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
        <div class="auth-container" style="background: var(--white); width: 100%; max-width: 480px; padding: 40px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 12px 32px rgba(87, 94, 112, 0.06);">

            <div style="text-align: center; margin-bottom: 24px;">
                <div style="width: 48px; height: 48px; background: var(--amber); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                        <path d="M12 15V17M6 11V7C6 5.4087 6.63214 3.88258 7.75736 2.75736C8.88258 1.63214 10.4087 1 12 1C13.5913 1 15.1174 1.63214 16.2426 2.75736C17.3679 3.88258 18 5.4087 18 7V11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 11H19C20.1046 11 21 11.8954 21 13V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V13C3 11.8954 3.89543 11 5 11Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink); margin-bottom: 8px;">Kata Sandi Baru</h2>
                <p style="font-size: 14px; color: var(--ink-60);">Silakan buat kata sandi baru yang kuat untuk akun Anda.</p>
            </div>

            @if($errors->any())
                <div style="background: #FEE2E2; color: #B91C1C; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 500; margin-bottom: 24px; border: 1px solid #FECACA;">
                    <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('password.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink-60); margin-bottom: 6px;">Mereset Akun Email</label>
                    <input type="text" value="{{ $email }}" disabled
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--cream); border-radius: 10px; font-size: 14px; background: #F3F4F6; color: var(--ink-60); cursor: not-allowed; outline: none;">
                </div>

                <div>
                    <label for="password" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); color: var(--ink); outline: none; transition: border-color 0.2s;">
                </div>

                <div>
                    <label for="password_confirmation" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); color: var(--ink); outline: none; transition: border-color 0.2s;">
                </div>

                <div style="margin-top: 8px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px; font-weight: 700; cursor: pointer; border: none; background: var(--amber); color: white; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>