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

    <div style="display:flex; flex-direction:column; gap:24px;">
        
        <div class="profile-card" style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171,168,159,.25); padding: 24px; display: flex; align-items: center; gap: 32px; margin-bottom: 8px;">

            <div class="identity-section" style="display:flex; align-items:center; gap:20px; min-width:280px;">
                <div class="avatar" style="width:80px; height:80px; border-radius:50%; background:var(--amber-bg); color:var(--amber); display:flex; align-items:center; justify-content:center; font-size:32px; font-family:'DM Serif Display', serif; font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($siswaUser->nama, 0, 1)) }}
                </div>
                <div>
                    <h2 style="margin:0; font-size:22px; font-weight:700; color:var(--ink);">{{ $siswaUser->nama }}</h2>
                    <p style="margin:4px 0 0; color:var(--ink-60); font-size:14px;">{{ $siswaUser->email }}</p>
                </div>
            </div>

            <div class="info-grid" style="flex:1; display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; border-left:1px solid rgba(171,168,159,.25); padding-left:32px;">

                @php
                $infoItems = [
                    ['label' => 'NISN', 'value' => $dataSiswa->nisn ?? '-'],
                    ['label' => 'Jenis Kelamin', 'value' => ($dataSiswa->jenis_kelamin ?? null) == 'L' ? 'Laki-laki' : ($dataSiswa->jenis_kelamin == 'P' ? 'Perempuan' : '-')],
                    ['label' => 'Angkatan', 'value' => $dataSiswa->angkatan ?? '-'],
                    ['label' => 'Status', 'value' => ($eksplorasi?->status == 'selesai' ? '✓ Selesai' : 'Belum Selesai'), 'is_badge' => true],
                    ['label' => 'Tgl Analisis', 'value' => $eksplorasi?->updated_at?->format('d M Y') ?? '-'],
                    ['label' => 'Guru BK', 'value' => Auth::user()->name ?? Auth::user()->nama]
                ];
                @endphp

                @foreach($infoItems as $item)
                <div>
                    <div style="font-size:11px; color:var(--ink-30); text-transform:uppercase; font-weight:700; margin-bottom:4px;">
                        {{ $item['label'] }}
                    </div>
                    @if(isset($item['is_badge']))
                    <div style="display:inline-block; background:{{ $eksplorasi?->status == 'selesai' ? '#ECFDF5' : '#FEF3C7' }}; color:{{ $eksplorasi?->status == 'selesai' ? '#059669' : '#D97706' }}; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700;">
                        {{ $item['value'] }}
                    </div>
                    @else
                    <div style="font-size:14px; font-weight:600; color:var(--ink);">
                        {{ $item['value'] }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

        </div>

    </div>
    
    
    @if(!empty($ml_data))
    @php
        
        if (!$ml_data) {
            echo "<div style='padding: 20px; background: #FEF2F2; color: #991B1B; border-radius: 12px;'>Data hasil analisis belum tersedia atau terjadi kesalahan pada AI.</div>";
            return;
        }

        
        $riasecScores = $ml_data['riasec_minat']['skor_raw'] ?? [
            'Realistic' => 0, 'Investigative' => 0, 'Artistic' => 0,
            'Social' => 0, 'Enterprising' => 0, 'Conventional' => 0
        ];

        arsort($riasecScores);
        $top3Keys = array_slice(array_keys($riasecScores), 0, 3);
        
        $hollandInitials = array_map(function($key) { return substr($key, 0, 1); }, $top3Keys);
        $hollandCode = implode(' ', $hollandInitials);
        $hollandTypes = implode(' - ', $top3Keys);

        
        $rekomendasi = array_slice($ml_data['rekomendasi_jurusan'] ?? [], 0, 3);
        $topFitScore = !empty($rekomendasi) ? round(($rekomendasi[0]['skor_kesesuaian'] / 5) * 100) : 0;

        
        $rumpunIlmu = $ml_data['analisis_akademik']['rumpun_ilmu'] ?? (!empty($rekomendasi) ? $rekomendasi[0]['rumpun_ilmu'] : 'Belum Diketahui');

        
        $chartData = [
            $riasecScores['Realistic'] ?? 0,
            $riasecScores['Investigative'] ?? 0,
            $riasecScores['Artistic'] ?? 0,
            $riasecScores['Social'] ?? 0,
            $riasecScores['Enterprising'] ?? 0,
            $riasecScores['Conventional'] ?? 0,
        ];

        
        $riasecConfig = [
            'Realistic' => ['color' => '#3B82F6', 'desc' => ['Suka praktik dan observasi', 'Menyukai alat/teknologi', 'Aktivitas fisik/lapangan']],
            'Investigative' => ['color' => '#8B5CF6', 'desc' => ['Analitis dan logis', 'Menyukai riset', 'Pemecahan masalah kompleks']],
            'Artistic' => ['color' => '#EC4899', 'desc' => ['Kreatif dan imajinatif', 'Inovatif', 'Kebebasan berekspresi']],
            'Social' => ['color' => '#F59E0B', 'desc' => ['Suka membantu/melatih', 'Komunikatif & empati', 'Kerja tim']],
            'Enterprising' => ['color' => '#10B981', 'desc' => ['Jiwa kepemimpinan', 'Persuasif/negosiator', 'Berorientasi target']],
            'Conventional' => ['color' => '#64748B', 'desc' => ['Terstruktur & rapi', 'Teliti pada detail', 'Suka keteraturan']]
        ];

        $maxScore = max(16, max($riasecScores)); 
    @endphp

    <div class="dashboard-hasil" style="animation: fadeIn 0.5s ease-in-out; margin-top: 16px;">

        
        <div style="background: var(--ink); padding: 20px 24px; border-radius: 16px; box-shadow: 0 4px 16px rgba(87, 94, 112, 0.08); margin-bottom: 24px; color: var(--white); display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; align-items: center;">
            
            <div style="flex: 1 1 180px;">
                <h3 style="font-size: 13px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Profil Minat Siswa</h3>
                <div style="font-size: 24px; font-weight: 800; margin-bottom: 4px;">Kode: <span style="color: var(--amber);">{{ $hollandCode }}</span></div>
                <div style="font-size: 14px; color: rgba(255,255,255,0.9);">Dominan: {{ $hollandTypes }}</div>
            </div>

            <div style="flex: 1 1 180px;">
                <h3 style="font-size: 13px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Kecondongan Rumpun</h3>
                <div style="font-size: 20px; font-weight: 800; color: #60A5FA; margin-bottom: 4px; line-height: 1.2;">{{ $rumpunIlmu }}</div>
                <div style="font-size: 13px; color: rgba(255,255,255,0.9);">Basis Akademik</div>
            </div>

            <div style="text-align: right; background: rgba(255,255,255,0.1); padding: 14px 20px; border-radius: 12px; flex-shrink: 0;">
                <div style="font-size: 12px; color: rgba(255,255,255,0.7); margin-bottom: 4px;">Kesesuaian Profil</div>
                <div style="font-size: 28px; font-weight: 800; color: #10B981;">{{ $topFitScore }}%</div>
            </div>

        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
            <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Skor RIASEC</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">

                    @foreach($riasecScores as $key => $score)
                    @php
                        $barWidth = ($score / $maxScore) * 100;
                        $color = $riasecConfig[$key]['color'];
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: var(--ink); margin-bottom: 4px;">
                            <span>{{ $key }}</span> <span>{{ $score }}</span>
                        </div>
                        <div style="height: 6px; background: var(--paper); border-radius: 99px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $barWidth }}%; background: {{ $color }}; border-radius: 99px;"></div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); display: flex; flex-direction: column;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 12px;">Grafik Profil RIASEC</h3>
                <div style="flex: 1; position: relative; min-height: 220px; display: flex; justify-content: center; align-items: center;">
                    <canvas id="riasecChart"></canvas>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 12px;">3 Karakter Dominan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">

                @php $medals = ['🥇', '🥈', '🥉']; $i = 0; @endphp
                @foreach($top3Keys as $key)
                <div style="background: var(--white); border: 1px solid rgba(171, 168, 159, 0.25); border-radius: 12px; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <div style="font-size: 20px;">{{ $medals[$i] }}</div>
                        <h4 style="font-size: 14px; font-weight: 700; color: {{ $riasecConfig[$key]['color'] }}; margin: 0;">{{ $key }}</h4>
                    </div>
                    <ul style="padding-left: 16px; font-size: 13px; color: var(--ink-60); margin: 0; line-height: 1.5;">
                        @foreach($riasecConfig[$key]['desc'] as $descItem)
                        <li>{{ $descItem }}</li>
                        @endforeach
                    </ul>
                </div>
                @php $i++; @endphp
                @endforeach

            </div>
        </div>

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Indikator Evaluasi AI</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">

                @php
                    $statusMinat = $ml_data['perbandingan_minat']['status'] ?? 'N/A';
                    $statusAkademik = $ml_data['perbandingan_akademik']['status'] ?? 'N/A';
                @endphp

                <div style="padding: 14px 16px; background: var(--paper); border-radius: 10px; border-left: 4px solid var(--amber);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 13px; font-weight: 700; color: var(--ink);">Tulisan vs Minat</span>
                        <span style="font-size: 11px; color: white; background: var(--amber); padding: 2px 6px; border-radius: 4px; font-weight: 700;">{{ $statusMinat }}</span>
                    </div>
                    <div style="font-size: 12px; color: var(--ink-60); line-height: 1.4;">
                        {{ $ml_data['perbandingan_minat']['penjelasan'] ?? '-' }}
                    </div>
                </div>

                <div style="padding: 14px 16px; background: var(--paper); border-radius: 10px; border-left: 4px solid #10B981;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 13px; font-weight: 700; color: var(--ink);">Profil vs Akademik</span>
                        <span style="font-size: 11px; color: white; background: #10B981; padding: 2px 6px; border-radius: 4px; font-weight: 700;">{{ $statusAkademik }}</span>
                    </div>
                    <div style="font-size: 12px; color: var(--ink-60); line-height: 1.4;">
                        {{ $ml_data['perbandingan_akademik']['penjelasan'] ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
            <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                <div style="margin-bottom: 16px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Top 3 Rekomendasi Studi</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($rekomendasi as $index => $jurusan)
                    @php
                        $persenJurusan = round(($jurusan['skor_kesesuaian'] / 5) * 100);
                        $isTop = $index === 0;
                    @endphp
                    <div style="background: {{ $isTop ? 'var(--amber-bg)' : 'var(--paper)' }}; border: 1px solid {{ $isTop ? '#EDD19B' : 'rgba(171, 168, 159, 0.25)' }}; padding: 14px; border-radius: 10px; display: flex; align-items: center; gap: 14px;">
                        <div style="width: 36px; height: 36px; background: {{ $isTop ? 'var(--amber)' : 'var(--cream)' }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 18px; color: {{ $isTop ? 'var(--white)' : 'var(--ink-60)' }};">{{ $index + 1 }}</div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 2px;">{{ $jurusan['program_studi'] }}</h4>
                            <div style="font-size: 11px; color: var(--ink-60); font-weight: 600;">{{ $jurusan['rumpun_ilmu'] }} | Target IPK: {{ $jurusan['prediksi_ipk'] }}</div>
                        </div>
                        <div style="text-align: right; min-width: 50px;">
                            <div style="font-size: 16px; font-weight: 800; color: {{ $isTop ? 'var(--amber)' : 'var(--ink)' }};">{{ $persenJurusan }}%</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                <div style="margin-bottom: 16px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Alasan AI</h3>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                    @foreach($rekomendasi as $jurusan)
                    <li style="display: flex; gap: 10px; align-items: flex-start; padding-bottom: 10px; border-bottom: 1px dashed var(--cream);">
                        <div style="background: #10B981; color: white; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-size: 10px; flex-shrink: 0; margin-top: 2px;">✓</div>
                        <div>
                            <strong style="font-size: 13px; color: var(--ink); display: block; margin-bottom: 2px;">{{ $jurusan['program_studi'] }}</strong>
                            <span style="font-size: 12px; color: var(--ink-60); line-height: 1.4;">{{ $jurusan['alasan'] }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div style="padding: 16px 20px; background: var(--paper); border-radius: 10px; border-left: 4px solid var(--amber); display: flex; gap: 14px; align-items: flex-start;">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: var(--amber); flex-shrink: 0; margin-top: 2px;">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div>
                <h4 style="font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 6px; margin-top: 0;">Catatan Guru BK</h4>
                <p style="font-size: 13px; color: var(--ink-60); line-height: 1.5; margin: 0;">
                    Data di atas merupakan hasil analisis terintegrasi untuk menjadi dasar pertimbangan konseling. Rekomendasi sistem bersifat analitik dan dapat disesuaikan kembali dengan observasi langsung Bapak/Ibu Guru di lapangan.
                </p>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('riasecChart').getContext('2d');
            const riasecScores = @json($chartData);

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
                    datasets: [{
                        label: 'Skor RIASEC',
                        data: riasecScores,
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: '#10B981',
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#10B981',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(171, 168, 159, 0.3)' },
                            grid: { color: 'rgba(171, 168, 159, 0.3)', circular: true },
                            pointLabels: {
                                font: { size: 11, family: "'DM Sans', sans-serif", weight: '600' },
                                color: '#4B5563'
                            },
                            ticks: { display: false, min: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1F2937',
                            padding: 10,
                            titleFont: { size: 12, family: "'DM Sans', sans-serif" },
                            bodyFont: { size: 13, weight: 'bold', family: "'DM Sans', sans-serif" },
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>

    @else
    
    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px; height: 100%; display: flex; flex-direction: column; justify-content: center; margin-top: 16px;">
        <div style="text-align: center; padding: 40px 0;">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 56px; height: 56px; color: var(--ink-30); margin: 0 auto 16px;">
                <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h4 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Belum Ada Laporan LENTERA</h4>
            <p style="font-size: 13px; color: var(--ink-60); max-width: 320px; margin: 0 auto;">Siswa ini belum menyelesaikan tahap tes akademik dan instrumen. Hasil pemetaan akan otomatis muncul setelah tahapan selesai.</p>
        </div>
    </div>
    @endif

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