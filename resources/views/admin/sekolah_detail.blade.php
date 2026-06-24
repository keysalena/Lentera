@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-detail-sekolah" style="animation: fadeIn 0.4s ease-in-out;">

    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--ink-60); text-decoration: none; font-size: 14px; font-weight: 600; margin-bottom: 16px; transition: color 0.2s;" onmouseover="this.style.color='var(--amber)';" onmouseout="this.style.color='var(--ink-60)';">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
                {{ $sekolah->nama_sekolah }}
            </h2>
            <p style="font-size: 15px; color: var(--ink-60);">
                Detail informasi mitra sekolah, kode lisensi, dan daftar pengguna terdaftar.
            </p>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px; margin-bottom: 32px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
        
        <div>
            <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Kode Lisensi</h4>
            <div style="display: inline-flex; align-items: center; gap: 12px; background: rgba(201, 123, 42, 0.1); padding: 8px 16px; border-radius: 8px; border: 1px dashed var(--amber);">
                <span style="font-size: 16px; font-weight: 700; color: var(--amber); letter-spacing: 1px;">{{ $sekolah->kode_lisensi }}</span>
            </div>
            <p style="font-size: 12px; color: var(--ink-60); margin-top: 8px;">Berikan kode ini kepada Guru BK saat mendaftar.</p>
        </div>

        <div>
            <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Alamat Sekolah</h4>
            <p style="font-size: 15px; font-weight: 500; color: var(--ink); line-height: 1.5; margin: 0;">
                {{ $sekolah->alamat }}
            </p>
        </div>

        <div>
            <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Tanggal Bergabung</h4>
            <p style="font-size: 15px; font-weight: 500; color: var(--ink); margin: 0;">
                {{ $sekolah->created_at->format('d F Y') }}
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 32px;">
        
        <div style="background: var(--white); border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center; background: #FAFAF9;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink);">Data Guru BK ({{ $gurus->count() }})</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60); border-bottom: 1px solid var(--cream);">NAMA GURU</th>
                        <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60); border-bottom: 1px solid var(--cream);">EMAIL</th>
                        <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60); border-bottom: 1px solid var(--cream);">TERDAFTAR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                    <tr style="border-bottom: 1px solid var(--cream);">
                        <td style="padding: 20px 32px; font-weight: 600; color: var(--ink);">{{ $guru->nama }}</td>
                        <td style="padding: 20px 32px; color: var(--ink-60); font-size: 14px;">{{ $guru->email }}</td>
                        <td style="padding: 20px 32px; color: var(--ink-60); font-size: 14px;">{{ $guru->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                            Belum ada Guru BK yang mendaftar menggunakan lisensi sekolah ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="background: var(--white); border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden; margin-bottom: 32px;">
            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center; background: #FAFAF9;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink);">Data Siswa ({{ $siswas->count() }})</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60); border-bottom: 1px solid var(--cream);">NAMA SISWA</th>
                        <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60); border-bottom: 1px solid var(--cream);">EMAIL</th>
                        <th style="padding: 16px 32px; text-align: left; font-size: 12px; color: var(--ink-60); border-bottom: 1px solid var(--cream);">TERDAFTAR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                    <tr style="border-bottom: 1px solid var(--cream);">
                        <td style="padding: 20px 32px; font-weight: 600; color: var(--ink);">{{ $siswa->nama }}</td>
                        <td style="padding: 20px 32px; color: var(--ink-60); font-size: 14px;">{{ $siswa->email }}</td>
                        <td style="padding: 20px 32px; color: var(--ink-60); font-size: 14px;">{{ $siswa->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                            Belum ada Siswa dari sekolah ini yang mendaftar di sistem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection