<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'LENTERA — Daftar')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="/css/style.css?v={{ time() }}">
</head>

<body>
    <div style="background: var(--cream); min-height: calc(100vh - 72px); display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
        <div class="auth-container" style="background: var(--white); width: 100%; max-width: 520px; padding: 40px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 12px 32px rgba(87, 94, 112, 0.06);">

            <div style="text-align: center; margin-bottom: 32px;">
                <div style="width: 48px; height: 48px; background: var(--amber); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                        <path d="M10 2L12.5 7.5L18 8.5L14 12.5L15 18L10 15.5L5 18L6 12.5L2 8.5L7.5 7.5L10 2Z" fill="white" />
                    </svg>
                </div>
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink); margin-bottom: 8px;">Buat Akun LENTERA</h2>
                <p style="font-size: 14px; color: var(--ink-60);">Lengkapi data di bawah untuk memulai eksplorasi kariermu</p>
            </div>

            <form action="{{ route('register') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Daftar Sebagai</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <label style="border: 2px solid var(--cream); background: var(--paper); padding: 12px; border-radius: 10px; display: flex; align-items: center; gap: 8px; cursor: pointer; justify-content: center;">
                            <input type="radio" id="role_siswa" name="role" value="siswa" {{ old('role', 'siswa') === 'siswa' ? 'checked' : '' }} style="accent-color: var(--amber);">
                            <span style="font-size: 14px; font-weight: 600; color: var(--ink);">Siswa</span>
                        </label>
                        <label style="border: 2px solid var(--cream); background: var(--paper); padding: 12px; border-radius: 10px; display: flex; align-items: center; gap: 8px; cursor: pointer; justify-content: center;">
                            <input type="radio" id="role_guru" name="role" value="guru" {{ old('role') === 'guru' ? 'checked' : '' }} style="accent-color: var(--amber);">
                            <span style="font-size: 14px; font-weight: 600; color: var(--ink-60);">Guru BK</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Keysa Lena Misdona"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none;">
                </div>

                <div>
                    <label for="email" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@sekolah.sch.id"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none;">
                    @error('email') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="school" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Asal Sekolah</label>
                    <select id="school" name="id_sekolah" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none; cursor: pointer;">
                        <option value="" disabled selected>Pilih asal sekolahmu...</option>
                        @foreach($sekolahs as $sekolah)
                        <option value="{{ $sekolah->id_sekolah }}" {{ old('id_sekolah') == $sekolah->id_sekolah ? 'selected' : '' }}>
                            {{ $sekolah->nama_sekolah }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div id="lisensi_container" style="display: none; background: rgba(201, 123, 42, 0.05); border: 1px dashed var(--amber); padding: 16px; border-radius: 10px;">
                    <label for="kode_lisensi" style="display: block; font-size: 13px; font-weight: 700; color: var(--amber); margin-bottom: 6px;">Kode Lisensi Sekolah</label>
                    <input type="text" id="kode_lisensi" name="kode_lisensi" value="{{ old('kode_lisensi') }}" placeholder="Contoh: LENTERA-XXXX-XXXX"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 8px; font-size: 14px; outline: none; background: var(--white);">
                    <p style="font-size: 11px; color: var(--ink-60); margin-top: 6px;">Diperlukan untuk memverifikasi Anda sebagai staf pendidik resmi.</p>
                    @error('kode_lisensi') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px; font-weight: 600;">{{ $message }}</p> @enderror
                </div>
                <!-- ── ANGKATAN (HANYA MUNCUL JIKA SISWA) ── -->
                <div id="angkatan_container" style="display: block;">
                    <label for="angkatan" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Tahun Masuk</label>
                    <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" required placeholder="Contoh: 2024"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none; transition: border-color 0.2s;">
                    @error('angkatan') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label for="password" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Kata Sandi</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter"
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none;">
                    </div>
                    <div>
                        <label for="password_confirmation" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Konfirmasi Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none;">
                    </div>
                </div>

                <div style="margin-top: 8px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px; font-weight: 700;">Buat Akun Baru</button>
                </div>
            </form>
            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--cream);">
                <p style="font-size: 14px; color: var(--ink-60);">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" style="color: var(--amber); font-weight: 700; text-decoration: none;">Masuk Sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const roleSiswa = document.getElementById('role_siswa');
        const roleGuru = document.getElementById('role_guru');

        const lisensiContainer = document.getElementById('lisensi_container');
        const kodeLisensiInput = document.getElementById('kode_lisensi');

        const angkatanContainer = document.getElementById('angkatan_container');
        const angkatanInput = document.getElementById('angkatan');

        function toggleFields() {
            if (roleGuru.checked) {
                // Tampilkan Lisensi Guru, Sembunyikan Angkatan Siswa
                lisensiContainer.style.display = 'block';
                kodeLisensiInput.setAttribute('required', 'required');

                angkatanContainer.style.display = 'none';
                angkatanInput.removeAttribute('required');
            } else {
                // Tampilkan Angkatan Siswa, Sembunyikan Lisensi Guru
                lisensiContainer.style.display = 'none';
                kodeLisensiInput.removeAttribute('required');

                angkatanContainer.style.display = 'block';
                angkatanInput.setAttribute('required', 'required');
            }
        }

        roleSiswa.addEventListener('change', toggleFields);
        roleGuru.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>