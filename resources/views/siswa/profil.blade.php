@extends('layouts.siswa')

@section('dashboard_content')
<div class="siswa-profil" style="animation: fadeIn 0.4s ease-in-out;">

    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border: 1px solid #34D399;">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color: #065F46; cursor:pointer; font-size: 20px; font-weight: bold;">&times;</button>
    </div>
    @endif

    <div style="margin-bottom: 32px;">
        <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink); margin-bottom: 8px;">Profil Saya</h2>
        <p style="font-size: 14px; color: var(--ink-60);">Lengkapi dan perbarui data diri Anda untuk akurasi laporan analisis karier.</p>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start;">

        <div style="flex: 2 1 500px; background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px;">
            <form action="{{ route('siswa.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: flex; flex-direction: column; gap: 32px;">

                    <div style="padding-bottom: 32px; border-bottom: 1px solid var(--cream);">
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Informasi Instansi & Akun</h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink-60); margin-bottom: 8px;">Alamat Email</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--cream); border-radius: 10px; font-size: 14px; background: #F3F4F6; color: var(--ink-60); cursor: not-allowed; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink-60); margin-bottom: 8px;">Asal Sekolah Mitra</label>
                                <input type="text" value="{{ optional($user->sekolah)->nama_sekolah ?? 'Data sekolah tidak ditemukan' }}" disabled
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--cream); border-radius: 10px; font-size: 14px; background: #F3F4F6; color: var(--ink-60); cursor: not-allowed; outline: none;">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Biodata Siswa</h3>

                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div>
                                <label for="nama" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required placeholder="Masukkan nama lengkap Anda"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; transition: border-color 0.2s;">
                                @error('nama') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="nisn" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">NISN (Nomor Induk Siswa Nasional)</label>
                                <input type="text" id="nisn" name="nisn"
                                    value="{{ old('nisn', Str::startsWith(optional($siswa)->nisn, 'S') ? '' : optional($siswa)->nisn) }}"
                                    required placeholder="Contoh: 0061234567"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; transition: border-color 0.2s;">
                                @error('nisn') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                            </div>
                            
                            <div style="margin-top: 0px; padding: 20px; background: rgba(16, 185, 129, 0.05); border: 1px dashed #10B981; border-radius: 12px;">
                                <label for="kode_lisensi" style="display: block; font-size: 13px; font-weight: 700; color: #065F46; margin-bottom: 8px;">
                                    Kode Lisensi Sekolah (Aktivasi Fitur Konsultasi Karier)
                                </label>

                                <div style="display: flex; gap: 8px;">
                                    <input type="text" id="kode_lisensi" name="kode_lisensi"
                                        value="{{ Auth::user()->id_sekolah ? 'TERAKTIVASI' : old('kode_lisensi') }}"
                                        placeholder="Masukkan kode lisensi..."
                                        {{ Auth::user()->id_sekolah ? 'disabled' : '' }}
                                        style="flex: 1; padding: 12px 16px; border: 1px solid #10B981; border-radius: 8px; font-size: 14px; background: {{ Auth::user()->id_sekolah ? '#f0f9f4' : 'white' }};">

                                    @if(!Auth::user()->id_sekolah)
                                    <button type="submit" style="background: #10B981; color: white; padding: 0 20px; border-radius: 8px; font-weight: 600; font-size: 13px; border: none; cursor: pointer;">
                                        Aktifkan
                                    </button>
                                    @else
                                    <span style="display: flex; align-items: center; color: #059669; font-size: 12px; font-weight: 600; padding: 0 10px;">
                                        ✓ Aktif
                                    </span>
                                    @endif
                                </div>

                                @if(!Auth::user()->id_sekolah)
                                <p style="font-size: 11px; color: #065F46; margin-top: 8px;">
                                    Masukkan kode unik dari Guru BK untuk membuka akses Konsultasi Karier dan fitur eksklusif lainnya.
                                </p>
                                @endif
                            </div>

                            <div>
                                <label for="jenis_kelamin" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; cursor: pointer;">
                                    <option value="" disabled>Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', optional($siswa)->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', optional($siswa)->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="angkatan" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Tahun Masuk</label>
                                <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan', optional($siswa)->angkatan) }}" required placeholder="Contoh: 2024 atau 2024/2025"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; transition: border-color 0.2s;">
                                @error('angkatan') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: var(--amber); color: var(--white); border: none; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <div style="flex: 1 1 300px; background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Keamanan Akun</h3>
            
            <form action="{{ route('siswa.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <div>
                        <label for="current_password" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Kata Sandi Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" required placeholder="Masukkan kata sandi saat ini"
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; transition: border-color 0.2s;">
                        @error('current_password') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter"
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; transition: border-color 0.2s;">
                        @error('password') <p style="color: #BA1A1A; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--white); color: var(--ink); outline: none; transition: border-color 0.2s;">
                    </div>

                </div>

                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: var(--ink); color: var(--white); border: none; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                        Perbarui Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>
    </div>
@endsection