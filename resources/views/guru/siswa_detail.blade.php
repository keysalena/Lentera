@extends('layouts.guru')
@section('title', 'LENTERA - Detail Profil Siswa')

@section('dashboard_content')
<div class="guru-siswa-detail" style="animation: fadeIn 0.4s ease-in-out;">

    <div style="margin-bottom: 24px;">
        <a href="javascript:void(0);" onclick="history.back();" style="display: inline-flex; align-items: center; gap: 8px; color: var(--ink-60); text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='var(--amber)';" onmouseout="this.style.color='var(--ink-60)';">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- ── GRID LAYOUT UTAMA (Kiri Profil, Kanan Data) ── -->
    <div style="display: grid; grid-template-columns: minmax(280px, 1fr) 2fr; gap: 24px; align-items: start;">

        <!-- ── KOLOM KIRI: KARTU PROFIL SINGKAT ── -->
        <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px; text-align: center; position: sticky; top: 24px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--amber-bg); color: var(--amber); display: flex; align-items: center; justify-content: center; font-size: 32px; font-family: 'DM Serif Display', serif; font-weight: 700; margin: 0 auto 16px;">
                {{ substr($siswaUser->nama, 0, 1) }}
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">{{ $siswaUser->nama }}</h3>
            <p style="font-size: 13px; color: var(--ink-60);">{{ $siswaUser->email }}</p>

            <div style="margin-top: 24px; text-align: left; padding-top: 24px; border-top: 1px dashed var(--ink-30);">
                <div style="margin-bottom: 16px;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: var(--ink-30); text-transform: uppercase;">NISN</span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ optional($dataSiswa)->nisn ?? 'Belum Diisi' }}
                    </span>
                </div>
                <div style="margin-bottom: 16px;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: var(--ink-30); text-transform: uppercase;">Jenis Kelamin</span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ optional($dataSiswa)->jenis_kelamin == 'L' ? 'Laki-laki' : (optional($dataSiswa)->jenis_kelamin == 'P' ? 'Perempuan' : 'Belum Diisi') }}
                    </span>
                </div>
                <div>
                    <span style="display: block; font-size: 11px; font-weight: 700; color: var(--ink-30); text-transform: uppercase;">Tahun Angkatan</span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ optional($dataSiswa)->angkatan ?? 'Belum Diisi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ── KOLOM KANAN: DATA EKSPLORASI & AI ── -->
        <div style="display: flex; flex-direction: column; gap: 24px;">

            <!-- Cek Apakah Ada Hasil Machine Learning -->
            @if($ml_data && isset($ml_data['karakter']))

            <!-- 1. RIWAYAT NILAI AKADEMIK -->
            @if(count($nilaiAkademik) > 0)
            <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Riwayat Nilai Rapor (Akademik)</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 16px;">
                    @foreach($nilaiAkademik as $nilai)
                    <div style="background: var(--paper); padding: 16px; border-radius: 10px; border: 1px solid var(--cream);">
                        <div style="font-size: 11px; color: var(--ink-60); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $nilai->mapel->nama_mapel }}">
                            {{ $nilai->mapel->nama_mapel }}
                        </div>
                        <div style="font-size: 18px; font-weight: 800; color: var(--ink);">{{ $nilai->nilai }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 2. PEMETAAN KEPRIBADIAN & RIASEC -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                <!-- Dominan Karakter -->
                <div style="background: var(--ink); padding: 28px; border-radius: 16px; position: relative; overflow: hidden;">
                    <div style="position: absolute; right: -20px; top: -40px; width: 140px; height: 140px; border-radius: 50%; border: 20px solid {{ $ml_data['karakter']['warna'] ?? 'var(--amber)' }}; opacity: 0.1;"></div>
                    <div style="position: relative; z-index: 10;">
                        <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.1); color: var(--white); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; margin-bottom: 12px; letter-spacing: 0.05em;">
                            PROFIL KEPRIBADIAN
                        </div>
                        <h5 style="font-size: 12px; font-weight: 700; color: {{ $ml_data['karakter']['warna'] ?? 'var(--amber-lt)' }}; margin-bottom: 4px; text-transform: uppercase;">
                            Dominan: {{ $ml_data['karakter']['tipe'] ?? '-' }}
                        </h5>
                        <h2 style="font-family: 'DM Serif Display', serif; font-size: 24px; color: var(--white); margin-bottom: 12px; line-height: 1.2;">
                            {!! str_replace('& ', '&<br>', $ml_data['karakter']['nama'] ?? '-') !!}
                        </h2>
                        <p style="font-size: 13px; color: rgba(255, 255, 255, 0.8); line-height: 1.6; margin: 0;">
                            {{ $ml_data['karakter']['deskripsi'] ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Grafik RIASEC -->
                <div style="background: var(--white); padding: 28px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Distribusi Holland Codes (RIASEC)</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @if(isset($ml_data['riasec_skor']))
                        @foreach($ml_data['riasec_skor'] as $tipe => $skor)
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: var(--ink); margin-bottom: 4px;">
                                <span>{{ $tipe }}</span>
                                <span>{{ round($skor * 100) }}%</span>
                            </div>
                            <div style="height: 6px; background: var(--cream); border-radius: 99px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $skor * 100 }}%; background: {{ $tipe == ($ml_data['karakter']['tipe'] ?? '') ? ($ml_data['karakter']['warna'] ?? 'var(--amber)') : 'var(--ink-30)' }}; border-radius: 99px;"></div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. TOP 3 JURUSAN -->
            @if(isset($ml_data['rekomendasi_jurusan']))
            <div style="background: var(--white); padding: 32px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h4 style="font-size: 16px; font-weight: 700; color: var(--ink);">Rekomendasi Program Studi</h4>
                    <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700;">AI MATCHED</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @foreach($ml_data['rekomendasi_jurusan'] as $index => $rekomendasi)
                    @php
                    $isTop = $index === 0;
                    $textColor = $isTop ? 'var(--amber)' : 'var(--ink)';
                    @endphp
                    <div style="background: {{ $isTop ? 'var(--amber-bg)' : 'var(--paper)' }}; border: 1px solid {{ $isTop ? '#EDD19B' : 'var(--cream)' }}; padding: 16px; border-radius: 12px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 40px; height: 40px; min-width: 40px; background: {{ $isTop ? 'var(--amber)' : 'var(--white)' }}; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 20px; color: {{ $isTop ? 'white' : 'var(--ink-60)' }}; border: {{ $isTop ? 'none' : '1px solid var(--cream)' }};">
                            {{ $rekomendasi['rank'] ?? ($index + 1) }}
                        </div>
                        <div style="flex: 1;">
                            <h5 style="font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 2px;">{{ $rekomendasi['jurusan'] ?? '-' }}</h5>
                            <p style="font-size: 12.5px; color: var(--ink-60); margin: 0; line-height: 1.4;">{{ $rekomendasi['alasan'] ?? '-' }}</p>
                        </div>
                        <div style="text-align: right; min-width: 60px;">
                            <div style="font-size: 11px; color: var(--ink-60); font-weight: 600; margin-bottom: 2px;">Kecocokan</div>
                            <div style="font-size: 18px; font-weight: 800; color: {{ $textColor }}">{{ $rekomendasi['match_score'] ?? '0' }}%</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @else
            <!-- PLACEHOLDER JIKA BELUM TES -->
            <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px; height: 100%; display: flex; flex-direction: column; justify-content: center;">
                <div style="text-align: center; padding: 40px 0;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 64px; height: 64px; color: var(--ink-30); margin: 0 auto 16px;">
                        <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h4 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Belum Ada Laporan LENTERA</h4>
                    <p style="font-size: 14px; color: var(--ink-60); max-width: 320px; margin: 0 auto;">Siswa ini belum menyelesaikan tahap input tes akademik dan tulisan tangan. Hasil pemetaan jurusan akan otomatis muncul di sini setelah tahapan diselesaikan.</p>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection