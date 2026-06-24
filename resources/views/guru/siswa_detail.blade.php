@extends('layouts.guru')

@section('dashboard_content')
<div class="guru-siswa-detail" style="animation: fadeIn 0.4s ease-in-out;">

    <!-- ── TOMBOL KEMBALI ── -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('guru.siswa') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--ink-60); text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='var(--amber)';" onmouseout="this.style.color='var(--ink-60)';">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Daftar Siswa
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
        
        <!-- ── KARTU PROFIL SINGKAT ── -->
        <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px; text-align: center;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--amber-bg); color: var(--amber); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 700; margin: 0 auto 16px;">
                {{ substr($siswa->nama, 0, 1) }}
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">{{ $siswa->nama }}</h3>
            <p style="font-size: 13px; color: var(--ink-60);">{{ $siswa->email }}</p>
            
            <div style="margin-top: 24px; text-align: left;">
                <div style="margin-bottom: 16px;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: var(--ink-30); text-transform: uppercase;">NISN</span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ Str::startsWith(optional($siswa->siswa)->nisn, 'S') ? 'Belum Diisi' : optional($siswa->siswa)->nisn }}
                    </span>
                </div>
                <div style="margin-bottom: 16px;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: var(--ink-30); text-transform: uppercase;">Jenis Kelamin</span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ optional($siswa->siswa)->jenis_kelamin == 'L' ? 'Laki-laki' : (optional($siswa->siswa)->jenis_kelamin == 'P' ? 'Perempuan' : 'Belum Diisi') }}
                    </span>
                </div>
                <div>
                    <span style="display: block; font-size: 11px; font-weight: 700; color: var(--ink-30); text-transform: uppercase;">Tahun Masuk</span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--ink);">{{ $siswa->angkatan }}</span>
                </div>
            </div>
        </div>

        <!-- ── KARTU HASIL EKSPLORASI (PLACEHOLDER) ── -->
        <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--cream); padding-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Laporan Hasil Eksplorasi</h3>
                <span style="background: #F3F4F6; color: var(--ink-60); padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">Data AI</span>
            </div>

            <div style="text-align: center; padding: 40px 0;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 64px; height: 64px; color: var(--cream); margin: 0 auto 16px;">
                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Belum Ada Laporan</h4>
                <p style="font-size: 14px; color: var(--ink-60); max-width: 300px; margin: 0 auto;">Siswa ini belum menyelesaikan tes asesmen karier. Laporan AI akan muncul di sini setelah tes diselesaikan.</p>
            </div>
        </div>

    </div>

</div>
@endsection