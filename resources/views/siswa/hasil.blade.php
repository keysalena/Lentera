@extends('layouts.siswa')
@section('title', 'LENTERA - Hasil Analisis')

@section('dashboard_content')
<div class="dashboard-hasil" style="animation: fadeIn 0.5s ease-in-out;">

    <!-- Alert Sukses (Dari redirect) -->
    @if(session('success'))
    <div style="background: #F0FDF4; color: #166534; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; border: 1px solid #A7F3D0;">
        {{ session('success') }}
    </div>
    @endif

    @if(!$ml_data)
    <div style="text-align: center; padding: 64px 20px; background: var(--white); border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25);">
        <h3 style="color: var(--ink); margin-bottom: 8px;">Data Analisis Kosong</h3>
        <p style="color: var(--ink-60);">Sistem tidak dapat menemukan hasil prediksi AI untuk profil Anda.</p>
    </div>
    @else

    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 24px; margin-bottom: 32px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; margin-bottom: 12px;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;"><path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                ANALISIS SELESAI
            </div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
                Hasil Eksplorasi Kariermu
            </h2>
            <p style="font-size: 15px; color: var(--ink-60); max-width: 600px;">
                Sistem LENTERA telah memproses integrasi nilai akademik dan ekstraksi karakter tulisan tanganmu menggunakan Machine Learning.
            </p>
        </div>
    </div>

    <!-- BARIS 1: PROFIL & INSIGHT KARIER DOMINAN -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; margin-bottom: 24px;">

        <!-- KARTU KEPRIBADIAN -->
        <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02); display: flex; flex-direction: column;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                Profil Kepribadian (RIASEC)
                <span style="font-size: 11px; font-weight: 500; color: var(--ink-30); background: var(--paper); padding: 4px 8px; border-radius: 6px;">Diekstrak dari Tulisan</span>
            </h3>

            <!-- Skoring Progress Bars Dinamis dari API RIASEC -->
            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 16px;">
                @foreach($ml_data['riasec_skor'] as $tipe => $skor)
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 4px;">
                        <span>{{ $tipe }}</span>
                        <span>{{ round($skor * 100) }}%</span>
                    </div>
                    <div style="height: 6px; background: var(--cream); border-radius: 99px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $skor * 100 }}%; background: {{ $tipe == $ml_data['karakter']['tipe'] ? $ml_data['karakter']['warna'] : 'var(--ink-30)' }}; border-radius: 99px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div style="margin-top: 24px; font-size: 13px; color: var(--ink-60);">
                Kekuatan Utama Anda:
                <ul style="margin-top: 8px; padding-left: 20px;">
                    @foreach($ml_data['karakter']['kekuatan'] as $kuat)
                        <li style="margin-bottom: 4px; color: var(--ink);"><strong>{{ $kuat }}</strong></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- KARTU INSIGHT DINAMIS -->
        <div style="background: var(--ink); padding: 32px; border-radius: 20px; box-shadow: 0 4px 24px rgba(87, 94, 112, 0.08); display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -20px; top: -40px; width: 160px; height: 160px; border-radius: 50%; border: 20px solid {{ $ml_data['karakter']['warna'] }}; opacity: 0.1;"></div>

            <div style="position: relative; z-index: 10;">
                <h3 style="font-size: 13px; font-weight: 700; color: {{ $ml_data['karakter']['warna'] }}; margin-bottom: 8px; letter-spacing: 0.05em; text-transform: uppercase;">
                    Tipe Karakter Anda: {{ $ml_data['karakter']['tipe'] }}
                </h3>
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 36px; color: var(--white); line-height: 1.1; margin-bottom: 16px;">
                    {!! str_replace('& ', '&<br>', $ml_data['karakter']['nama']) !!}
                </h2>

                <div style="background: rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 16px; margin-top: 24px;">
                    <h4 style="font-size: 12px; font-weight: 700; color: {{ $ml_data['karakter']['warna'] }}; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;"><path d="M13 10V3L4 14H11V21L20 10H13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        LENTERA Insight
                    </h4>
                    <p style="font-size: 13.5px; color: rgba(255, 255, 255, 0.85); line-height: 1.6; margin: 0;">
                        {{ $ml_data['karakter']['deskripsi'] }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- BARIS 2: REKOMENDASI JURUSAN -->
    <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02);">
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Top 3 Rekomendasi Jurusan</h3>
            <p style="font-size: 13px; color: var(--ink-60);">Daftar program studi perguruan tinggi yang memiliki tingkat kecocokan paling tinggi dengan data analisis Anda.</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- LOOPING REKOMENDASI DARI FASTAPI -->
            @foreach($ml_data['rekomendasi_jurusan'] as $index => $rekomendasi)
                @php
                    $isTop1 = $index === 0;
                    $bgCard = $isTop1 ? 'var(--amber-bg)' : 'var(--paper)';
                    $borderCard = $isTop1 ? '#EDD19B' : 'rgba(171, 168, 159, 0.25)';
                    $bgRank = $isTop1 ? 'var(--amber)' : 'var(--cream)';
                    $textRank = $isTop1 ? 'var(--white)' : 'var(--ink-60)';
                    $textColor = $isTop1 ? 'var(--amber)' : 'var(--ink)';
                    $barBg = $isTop1 ? 'var(--amber)' : 'var(--ink-30)';
                @endphp
                <div style="background: {{ $bgCard }}; border: 1px solid {{ $borderCard }}; padding: 20px; border-radius: 14px; display: flex; align-items: center; gap: 20px; transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateX(4px)';" onmouseout="this.style.transform='none';">
                    <div style="width: 48px; height: 48px; background: {{ $bgRank }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 24px; color: {{ $textRank }};">
                        {{ $rekomendasi['rank'] }}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">{{ $rekomendasi['jurusan'] }}</h4>
                        <p style="font-size: 13px; color: var(--ink-60);">{{ $rekomendasi['alasan'] }}</p>
                    </div>
                    <div style="text-align: right; min-width: 100px;">
                        <div style="font-size: {{ $isTop1 ? '24px' : '20px' }}; font-weight: 800; color: {{ $textColor }}; margin-bottom: 4px;">{{ $rekomendasi['match_score'] }}%</div>
                        <div style="height: 6px; background: rgba(0,0,0,0.05); border-radius: 99px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $rekomendasi['match_score'] }}%; background: {{ $barBg }}; border-radius: 99px;"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- FOOTER CATATAN -->
        <div style="margin-top: 24px; padding: 16px; background: var(--paper); border-radius: 10px; border-left: 4px solid var(--amber); display: flex; gap: 12px; align-items: flex-start;">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: var(--amber); flex-shrink: 0; margin-top: 2px;"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            <p style="font-size: 13px; color: var(--ink-60); line-height: 1.5; margin: 0;">
                <strong>Tindak Lanjut:</strong> Hasil analisis ini adalah rekomendasi awal dari mesin AI. Langkah berikutnya, hasil ini akan divalidasi oleh <strong>Guru BK</strong> di sekolahmu sebagai bahan pertimbangan dalam sesi konsultasi tatap muka.
            </p>
        </div>
    </div>
    @endif
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection