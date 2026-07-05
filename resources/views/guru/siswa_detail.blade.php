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
        <!-- ── KARTU PROFIL SISWA (HORIZONTAL) ── -->
        <div class="profile-card" style="background: var(--white); border-radius: 18px; border: 1px solid rgba(171,168,159,.25); padding: 30px; display: flex; align-items: center; gap: 40px; margin-bottom: 24px;">

            <div class="identity-section" style="display:flex; align-items:center; gap:24px; min-width:320px;">
                <div class="avatar" style="width:90px; height:90px; border-radius:50%; background:var(--amber-bg); color:var(--amber); display:flex; align-items:center; justify-content:center; font-size:36px; font-family:'DM Serif Display', serif; font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($siswaUser->nama, 0, 1)) }}
                </div>
                <div>
                    <h2 style="margin:0; font-size:26px; font-weight:700; color:var(--ink);">{{ $siswaUser->nama }}</h2>
                    <p style="margin:6px 0 0; color:var(--ink-60); font-size:15px;">{{ $siswaUser->email }}</p>
                </div>
            </div>

            <div class="info-grid" style="flex:1; display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; border-left:1px solid rgba(171,168,159,.25); padding-left:35px;">

                @php
                $infoItems = [
                ['label' => 'NISN', 'value' => $dataSiswa->nisn ?? '-'],
                ['label' => 'Jenis Kelamin', 'value' => ($dataSiswa->jenis_kelamin ?? null) == 'L' ? 'Laki-laki' : ($dataSiswa->jenis_kelamin == 'P' ? 'Perempuan' : '-')],
                ['label' => 'Angkatan', 'value' => $dataSiswa->angkatan ?? '-'],
                ['label' => 'Status Analisis', 'value' => ($eksplorasi?->status == 'selesai' ? '✓ Selesai' : 'Belum Selesai'), 'is_badge' => true],
                ['label' => 'Tanggal Analisis', 'value' => $eksplorasi?->updated_at?->format('d M Y') ?? '-'],
                ['label' => 'Guru BK', 'value' => Auth::user()->name ?? Auth::user()->nama]
                ];
                @endphp

                @foreach($infoItems as $item)
                <div>
                    <div style="font-size:11px; color:var(--ink-30); text-transform:uppercase; font-weight:700; margin-bottom:6px;">
                        {{ $item['label'] }}
                    </div>
                    @if(isset($item['is_badge']))
                    <div style="display:inline-block; background:{{ $eksplorasi?->status == 'selesai' ? '#ECFDF5' : '#FEF3C7' }}; color:{{ $eksplorasi?->status == 'selesai' ? '#059669' : '#D97706' }}; padding:5px 12px; border-radius:999px; font-size:13px; font-weight:700;">
                        {{ $item['value'] }}
                    </div>
                    @else
                    <div style="font-size:15px; font-weight:600; color:var(--ink);">
                        {{ $item['value'] }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

        </div>

    </div>
    <!-- Cek Apakah Ada Hasil Machine Learning -->
    @if(!empty($ml_data))
    @php
    // --- 1. Pengecekan Data ML ---
    if (!$ml_data) {
    echo "<div style='padding: 20px; background: #FEF2F2; color: #991B1B; border-radius: 12px;'>Data hasil analisis belum tersedia atau terjadi kesalahan pada AI. Silakan ulangi finalisasi data.</div>";
    return;
    }

    // --- 2. Mengolah Skor RIASEC ---
    // Mengambil skor (dari skor_raw instrumen minat atau bisa juga dari riasec_karakter)
    $riasecScores = $ml_data['riasec_minat']['skor_raw'] ?? [
    'Realistic' => 0, 'Investigative' => 0, 'Artistic' => 0,
    'Social' => 0, 'Enterprising' => 0, 'Conventional' => 0
    ];

    // Urutkan skor dari yang tertinggi ke terendah
    arsort($riasecScores);

    // Ambil Top 3
    $top3Keys = array_slice(array_keys($riasecScores), 0, 3);

    // Buat Kode Holland (Huruf Depan)
    $hollandInitials = array_map(function($key) { return substr($key, 0, 1); }, $top3Keys);
    $hollandCode = implode(' ', $hollandInitials);
    $hollandTypes = implode(' - ', $top3Keys);

    // --- 3. Mengolah Rekomendasi Jurusan ---
    $rekomendasi = $ml_data['rekomendasi_jurusan'] ?? [];
    // Hitung persentase kecocokan dari top rekomendasi (asumsi skala maksimal API adalah 5.0)
    $topFitScore = !empty($rekomendasi) ? round(($rekomendasi[0]['skor_kesesuaian'] / 5) * 100) : 0;

    // --- 4. Data untuk Chart.js (Wajib berurutan R-I-A-S-E-C) ---
    $chartData = [
    $riasecScores['Realistic'] ?? 0,
    $riasecScores['Investigative'] ?? 0,
    $riasecScores['Artistic'] ?? 0,
    $riasecScores['Social'] ?? 0,
    $riasecScores['Enterprising'] ?? 0,
    $riasecScores['Conventional'] ?? 0,
    ];

    // --- 5. Konfigurasi Warna & Deskripsi ---
    $riasecConfig = [
    'Realistic' => ['color' => '#3B82F6', 'desc' => ['Suka praktik dan observasi nyata', 'Menyukai alat, mesin, atau teknologi', 'Senang beraktivitas yang melibatkan fisik/lapangan']],
    'Investigative' => ['color' => '#8B5CF6', 'desc' => ['Analitis dan sangat logis', 'Menyukai penelitian dan riset', 'Senang memecahkan masalah kompleks']],
    'Artistic' => ['color' => '#EC4899', 'desc' => ['Kreatif dan kaya imajinasi', 'Inovatif dalam mencari solusi', 'Menyukai kebebasan dalam berekspresi']],
    'Social' => ['color' => '#F59E0B', 'desc' => ['Suka membantu, melatih, dan merawat orang lain', 'Komunikatif dan memiliki empati tinggi', 'Lebih suka bekerja dalam tim daripada sendirian']],
    'Enterprising' => ['color' => '#10B981', 'desc' => ['Memiliki jiwa kepemimpinan yang kuat', 'Persuasif dan pSiswai bernegosiasi', 'Berorientasi pada target dan pencapaian']],
    'Conventional' => ['color' => '#64748B', 'desc' => ['Terstruktur, rapi, dan sistematis', 'Sangat teliti terhadap detail', 'Menyukai keteraturan dan instruksi yang jelas']]
    ];

    $maxScore = max(16, max($riasecScores)); // Patokan bar panjang maksimum
    @endphp

    <div class="dashboard-hasil" style="animation: fadeIn 0.5s ease-in-out;">

        <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 24px; margin-bottom: 32px;">
            <div>
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
                    Hasil Analisis Potensi Siswa </h2>
                <p style="font-size: 15px; color: var(--ink-60); max-width: 700px; line-height: 1.6;">
                    Berikut merupakan hasil analisis potensi akademik dan minat karier siswa berdasarkan integrasi data nilai akademik, tulisan tangan, kemampuan diri, serta instrumen Kunci Karier Holland (RIASEC) yang diproses menggunakan Machine Learning. Informasi ini dapat digunakan Guru BK sebagai dasar dalam proses pendampingan dan konsultasi karier siswa. </p>
            </div>
        </div>

        <div style="background: var(--ink); padding: 24px 32px; border-radius: 20px; box-shadow: 0 4px 24px rgba(87, 94, 112, 0.08); margin-bottom: 24px; color: var(--white); display: flex; flex-wrap: wrap; gap: 24px; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 14px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Profil Minat Siswa</h3>
                <div style="font-size: 28px; font-weight: 800; margin-bottom: 4px;">Kode Holland: <span style="color: var(--amber);">{{ $hollandCode }}</span></div>
                <div style="font-size: 15px; color: rgba(255,255,255,0.9);">Tipe Dominan: {{ $hollandTypes }}</div>
            </div>
            <div style="text-align: right; background: rgba(255,255,255,0.1); padding: 16px 24px; border-radius: 16px;">
                <div style="font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 4px;">Persentase Kesesuaian Profil</div>
                <div style="font-size: 32px; font-weight: 800; color: #10B981;">{{ $topFitScore }}%</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; margin-bottom: 24px;">
            <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02);">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Skor RIASEC</h3>
                <div style="display: flex; flex-direction: column; gap: 14px;">

                    @foreach($riasecScores as $key => $score)
                    @php
                    $barWidth = ($score / $maxScore) * 100;
                    $color = $riasecConfig[$key]['color'];
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">
                            <span>{{ $key }}</span> <span>{{ $score }}</span>
                        </div>
                        <div style="height: 8px; background: var(--paper); border-radius: 99px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $barWidth }}%; background: {{ $color }}; border-radius: 99px; transition: 1s ease-in-out;"></div>
                        </div>
                    </div>
                    @endforeach

                </div>
                <p style="font-size: 12px; color: var(--ink-60); margin-top: 16px; background: var(--paper); padding: 12px; border-radius: 8px;">
                    <em>Semakin tinggi skor, semakin sesuai karakteristik tersebut dengan minat dan kepribadian Siswa.</em>
                </p>
            </div>

            <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02); display: flex; flex-direction: column;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Grafik Profil RIASEC</h3>
                <div style="flex: 1; position: relative; min-height: 250px; display: flex; justify-content: center; align-items: center;">
                    <canvas id="riasecChart"></canvas>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">3 Karakter Dominan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">

                @php $medals = ['🥇', '🥈', '🥉']; $i = 0; @endphp
                @foreach($top3Keys as $key)
                <div style="background: var(--white); border: 1px solid rgba(171, 168, 159, 0.25); border-radius: 16px; padding: 24px;">
                    <div style="font-size: 24px; margin-bottom: 8px;">{{ $medals[$i] }}</div>
                    <h4 style="font-size: 16px; font-weight: 700; color: {{ $riasecConfig[$key]['color'] }}; margin-bottom: 12px;">{{ $key }} (Dominan {{ $i+1 }})</h4>
                    <ul style="padding-left: 20px; font-size: 14px; color: var(--ink-60); margin: 0; line-height: 1.6;">
                        @foreach($riasecConfig[$key]['desc'] as $descItem)
                        <li>{{ $descItem }}</li>
                        @endforeach
                    </ul>
                </div>
                @php $i++; @endphp
                @endforeach

            </div>
        </div>

        <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); margin-bottom: 24px; max-width: 100%;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Indikator Evaluasi AI</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">

                @php
                $statusMinat = $ml_data['perbandingan_minat']['status'] ?? 'N/A';
                $statusAkademik = $ml_data['perbandingan_akademik']['status'] ?? 'N/A';
                @endphp

                <div style="padding: 16px 20px; background: var(--paper); border-radius: 12px; border-left: 4px solid var(--amber);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 14px; font-weight: 700; color: var(--ink);">Kesesuaian Analisis Tulisan dengan Instrumen Minat</span>
                        <span style="font-size: 12px; color: white; background: var(--amber); padding: 4px 8px; border-radius: 6px; font-weight: 700;">{{ $statusMinat }}</span>
                    </div>
                    <div style="font-size: 13px; color: var(--ink-60); line-height: 1.5;">
                        {{ $ml_data['perbandingan_minat']['penjelasan'] ?? '-' }}
                    </div>
                </div>

                <div style="padding: 16px 20px; background: var(--paper); border-radius: 12px; border-left: 4px solid #10B981;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 14px; font-weight: 700; color: var(--ink);">Kesesuaian Profil Minat dengan Prestasi Akademik</span>
                        <span style="font-size: 12px; color: white; background: #10B981; padding: 4px 8px; border-radius: 6px; font-weight: 700;">{{ $statusAkademik }}</span>
                    </div>
                    <div style="font-size: 13px; color: var(--ink-60); line-height: 1.5;">
                        {{ $ml_data['perbandingan_akademik']['penjelasan'] ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; margin-bottom: 24px;">
            <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02);">
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Rekomendasi Program Studin</h3>
                    <p style="font-size: 13px; color: var(--ink-60);">Daftar program studi yang memiliki tingkat kecocokan tertinggi dengan profil Siswa.</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($rekomendasi as $index => $jurusan)
                    @php
                    $persenJurusan = round(($jurusan['skor_kesesuaian'] / 5) * 100);
                    $isTop = $index === 0;
                    @endphp
                    <div style="background: {{ $isTop ? 'var(--amber-bg)' : 'var(--paper)' }}; border: 1px solid {{ $isTop ? '#EDD19B' : 'rgba(171, 168, 159, 0.25)' }}; padding: 20px; border-radius: 14px; display: flex; align-items: center; gap: 20px; transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateX(4px)';" onmouseout="this.style.transform='none';">
                        <div style="width: 48px; height: 48px; background: {{ $isTop ? 'var(--amber)' : 'var(--cream)' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 24px; color: {{ $isTop ? 'var(--white)' : 'var(--ink-60)' }};">{{ $index + 1 }}</div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">{{ $jurusan['program_studi'] }}</h4>
                            <div style="font-size: 12px; color: var(--ink-60); font-weight: 600;">Rumpun: {{ $jurusan['rumpun_ilmu'] }} | Target IPK: {{ $jurusan['prediksi_ipk'] }}</div>
                        </div>
                        <div style="text-align: right; min-width: 80px;">
                            <div style="font-size: {{ $isTop ? '24px' : '20px' }}; font-weight: 800; color: {{ $isTop ? 'var(--amber)' : 'var(--ink)' }}; margin-bottom: 4px;">{{ $persenJurusan }}%</div>
                            <div style="height: 6px; background: rgba(0,0,0,0.05); border-radius: 99px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $persenJurusan }}%; background: {{ $isTop ? 'var(--amber)' : 'var(--ink-30)' }}; border-radius: 99px;"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02);">
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Mengapa Jurusan Ini Direkomendasikan?</h3>
                    <p style="font-size: 13px; color: var(--ink-60);">Alasan dari sistem AI berdasarkan silang data profilmu.</p>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px;">
                    @foreach($rekomendasi as $jurusan)
                    <li style="display: flex; gap: 12px; align-items: flex-start; padding-bottom: 12px; border-bottom: 1px dashed var(--cream);">
                        <div style="background: #10B981; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; margin-top: 2px;">✓</div>
                        <div>
                            <strong style="font-size: 14px; color: var(--ink); display: block; margin-bottom: 4px;">{{ $jurusan['program_studi'] }}</strong>
                            <span style="font-size: 13px; color: var(--ink-60); line-height: 1.5;">{{ $jurusan['alasan'] }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div style="margin-top: 8px; padding: 20px; background: var(--paper); border-radius: 12px; border-left: 4px solid var(--amber); display: flex; gap: 16px; align-items: flex-start;">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; color: var(--amber); flex-shrink: 0; margin-top: 2px;">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div>
                <h4 style="font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 8px; margin-top: 0;">Rekomendasi untuk Guru BK</h4>
                <p style="font-size: 14px; color: var(--ink-60); line-height: 1.6; margin-bottom: 8px;">
                    Hasil analisis ini dapat digunakan sebagai bahan diskusi bersama siswa dan orang tua dalam menentukan pilihan program studi maupun arah karier. Rekomendasi dari sistem Machine Learning tidak bersifat mutlak, melainkan menjadi pendukung keputusan berdasarkan data minat, kemampuan, prestasi akademik, dan hasil analisis tulisan tangan siswa.
            </div>
        </div>

    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('riasecChart').getContext('2d');

            // Data di passing langsung dari variabel PHP $chartData
            const riasecScores = @json($chartData);

            const riasecChart = new Chart(ctx, {
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
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                color: 'rgba(171, 168, 159, 0.3)'
                            },
                            grid: {
                                color: 'rgba(171, 168, 159, 0.3)',
                                circular: true
                            },
                            pointLabels: {
                                font: {
                                    size: 12,
                                    family: "'DM Sans', sans-serif",
                                    weight: '600'
                                },
                                color: '#4B5563'
                            },
                            ticks: {
                                display: false,
                                min: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1F2937',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                family: "'DM Sans', sans-serif"
                            },
                            bodyFont: {
                                size: 14,
                                weight: 'bold',
                                family: "'DM Sans', sans-serif"
                            },
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>

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