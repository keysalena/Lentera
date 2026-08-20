@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-summary" style="animation: fadeIn 0.4s ease-in-out;">

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
                Dashboard Super Admin
            </h2>
            <p style="font-size: 15px; color: var(--ink-60);">
                Pantau statistik penggunaan dan kelola mitra sekolah di platform LENTERA.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.sekolah') }}" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Tambah Sekolah Mitra
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
            <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Sekolah Terdaftar</h4>
            <div style="font-size: 32px; font-weight: 800; color: var(--ink);">{{ number_format($total_sekolah) }}</div>
        </div>

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
            <h4 style="font-size: 12px; font-weight: 700; color: var(--amber); text-transform: uppercase; margin-bottom: 8px;">Total Guru BK</h4>
            <div style="font-size: 32px; font-weight: 800; color: var(--amber);">{{ number_format($total_guru) }}</div>
            <p style="font-size: 12px; color: var(--ink-30);">Akun pendidik terdaftar</p>
        </div>

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
            <h4 style="font-size: 12px; font-weight: 700; color: #0284C7; text-transform: uppercase; margin-bottom: 8px;">Total Siswa</h4>
            <div style="font-size: 32px; font-weight: 800; color: #0284C7;">{{ number_format($total_siswa) }}</div>
            <p style="font-size: 12px; color: var(--ink-30);">Akun siswa terdaftar</p>
        </div>

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
            <h4 style="font-size: 12px; font-weight: 700; color: #10B981; text-transform: uppercase; margin-bottom: 8px;">Analisis AI Selesai</h4>
            <div style="font-size: 32px; font-weight: 800; color: #10B981;">{{ number_format($total_analisis) }}</div>
            <p style="font-size: 12px; color: var(--ink-30);">Total laporan di-generate</p>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden; margin-bottom: 32px;">
        <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Daftar Mitra Sekolah</h3>
            <input type="text" placeholder="Cari nama sekolah..." style="padding: 10px 16px; border: 1px solid var(--ink-30); border-radius: 8px; width: 220px; outline: none; font-size: 13px;">
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #FAFAF9;">
                <tr>
                    <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA SEKOLAH</th>
                    <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60);">GURU BK</th>
                    <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60);">SISWA AKTIF</th>
                    <th style="padding: 16px 32px; text-align: right; font-size: 12px; color: var(--ink-60);">KELOLA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sekolahs as $sekolah)
                <tr style="border-top: 1px solid var(--cream);">
                    <td style="padding: 20px 32px;">
                        <div style="font-weight: 700; color: var(--ink);">{{ $sekolah->nama_sekolah }}</div>
                        <div style="font-size: 12px; color: var(--ink-60);">Bergabung sejak {{ $sekolah->created_at->format('M Y') }}</div>
                    </td>
                    <td style="padding: 20px 32px; font-weight: 600;">
                        {{ $sekolah->users->where('role.nama_role', 'guru')->count() }} Akun
                    </td>
                    <td style="padding: 20px 32px; font-weight: 600;">
                        {{ $sekolah->users->where('role.nama_role', 'siswa')->count() }} Siswa
                    </td>
                    <td style="padding: 20px 32px; text-align: right;">
                        <a href="{{ route('admin.sekolah.detail', $sekolah->id_sekolah) }}" style="color: var(--amber); font-weight: 600; font-size: 13px; text-decoration: none;">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection