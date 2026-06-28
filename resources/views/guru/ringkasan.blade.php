@extends('layouts.guru')

@section('dashboard_content')
<div class="guru-summary" style="animation: fadeIn 0.4s ease-in-out;">

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
        <div style="margin-bottom: 32px;">
            <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">

                <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin: 0;">
                    Dashboard Monitoring BK
                </h2>

                <div style="background: rgba(16, 185, 129, 0.05); border: 1px dashed #10B981; border-radius: 12px; padding: 10px 16px; display: inline-flex; align-items: center; gap: 12px;">
                    <div style="background: rgba(16, 185, 129, 0.15); color: #065F46; padding: 6px; border-radius: 8px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; color: var(--ink-60);">
                            <rect x="7" y="11" width="10" height="7" rx="2" ry="2"></rect>
                            <path d="M9 11V7a3 3 0 0 1 6 0v4"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 11px; font-weight: 700; color: #065F46; text-transform: uppercase;">Kode Siswa:</span>
                            <span style="font-size: 16px; font-weight: 800; color: var(--ink); font-family: monospace;">{{ $kode_lisensi_siswa }}</span>
                            <button onclick="copyLicense(event)"
                                style="background: var(--white); border: 1px solid #10B981; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; color: #10B981; cursor: pointer; transition: 0.2s;"
                                onmouseover="this.style.background='#10B981'; this.style.color='white';"
                                onmouseout="this.style.background='white'; this.style.color='#10B981';">
                                Salin
                            </button>

                            <script>
                                function copyLicense(event) {
                                    const text = @js($kode_lisensi_siswa);

                                    if (navigator.clipboard && window.isSecureContext) {
                                        navigator.clipboard.writeText(text)
                                            .then(() => showToast(event, 'Kode berhasil disalin!'))
                                            .catch(() => fallbackCopy(text, event));
                                    } else {
                                        fallbackCopy(text, event);
                                    }
                                }

                                function fallbackCopy(text, event) {
                                    const textarea = document.createElement('textarea');
                                    textarea.value = text;
                                    textarea.style.position = 'fixed';
                                    textarea.style.left = '-9999px';
                                    textarea.style.top = '-9999px';

                                    document.body.appendChild(textarea);
                                    textarea.focus();
                                    textarea.select();

                                    try {
                                        document.execCommand('copy');
                                        showToast(event, 'Kode berhasil disalin!');
                                    } catch (err) {
                                        showToast(event, 'Gagal menyalin!', true);
                                    }

                                    document.body.removeChild(textarea);
                                }

                                function showToast(event, message, error = false) {
                                    const oldToast = document.getElementById('copy-toast');
                                    if (oldToast) oldToast.remove();

                                    const toast = document.createElement('div');
                                    toast.id = 'copy-toast';
                                    toast.innerText = message;

                                    toast.style.position = 'fixed';
                                    toast.style.left = (event.clientX - 20) + 'px';
                                    toast.style.top = (event.clientY + 25) + 'px';
                                    toast.style.padding = '6px 10px';
                                    toast.style.fontSize = '12px';
                                    toast.style.borderRadius = '6px';
                                    toast.style.background = error ? '#EF4444' : '#10B981';
                                    toast.style.color = '#fff';
                                    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,.15)';
                                    toast.style.zIndex = '99999';
                                    toast.style.opacity = '0';
                                    toast.style.transition = 'opacity .2s ease, transform .2s ease';
                                    toast.style.transform = 'translateY(-5px)';
                                    toast.style.pointerEvents = 'none';

                                    document.body.appendChild(toast);

                                    requestAnimationFrame(() => {
                                        toast.style.opacity = '1';
                                        toast.style.transform = 'translateY(0)';
                                    });

                                    setTimeout(() => {
                                        toast.style.opacity = '0';
                                        toast.style.transform = 'translateY(-5px)';

                                        setTimeout(() => {
                                            toast.remove();
                                        }, 200);
                                    }, 1500);
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>

            <p style="font-size: 15px; color: var(--ink-60); margin-top: 12px;">
                Pantau hasil analisis eksplorasi karier siswa <span style="font-weight: 700; color: var(--amber);">{{ $nama_sekolah }}</span>.
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
            <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Total Partisipasi</h4>
            <div style="font-size: 32px; font-weight: 800; color: var(--ink);">{{ $total_siswa }}</div>
            <p style="font-size: 12px; color: var(--ink-30);">Siswa terdaftar di platform</p>
        </div>

        <a href="{{ route('guru.dominasi') ?? '#' }}" style="text-decoration: none; display: block;">
            <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); cursor: pointer; transition: box-shadow 0.2s, transform 0.2s;" onmouseover="this.style.boxShadow='0 4px 14px rgba(0,0,0,0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                <h4 style="font-size: 12px; font-weight: 700; color: var(--amber); text-transform: uppercase; margin-bottom: 8px;">Dominansi Bidang</h4>
                <div style="font-size: 32px; font-weight: 800; color: var(--amber);">{{ $bidang_dominan }}</div>
                <p style="font-size: 12px; color: var(--ink-30);">Klik untuk lihat rincian per siswa</p>
            </div>
        </a>

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
            <h4 style="font-size: 12px; font-weight: 700; color: #10B981; text-transform: uppercase; margin-bottom: 8px;">Laporan Selesai</h4>
            <div style="font-size: 32px; font-weight: 800; color: #10B981;">{{ $laporan_diakses }}</div>
            <p style="font-size: 12px; color: var(--ink-30);">Siswa telah melihat hasil akhirnya</p>
        </div>
    </div>

    <div style="background: var(--white); padding: 32px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Aktivitas Tes Terkini</h3>
            <a href="{{ route('guru.siswa') }}" style="font-size: 13px; color: var(--amber); font-weight: 600; text-decoration: none;">Lihat Semua Siswa</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            @forelse($aktivitas_terkini as $aktivitas)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border: 1px solid var(--cream); border-radius: 12px; background: var(--paper); transition: background 0.2s;" onmouseover="this.style.background='var(--white)';" onmouseout="this.style.background='var(--paper)';">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--amber-bg); color: var(--amber); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; font-family: 'DM Serif Display', serif;">
                        {{ substr(optional($aktivitas->akun_siswa)->nama ?? 'S', 0, 1) }}
                    </div>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--ink); margin: 0 0 4px 0;">
                            {{ optional($aktivitas->akun_siswa)->nama ?? 'Siswa Tidak Diketahui' }}
                        </h4>
                        <div style="font-size: 12px; color: var(--ink-60);">
                            Terakhir diperbarui: {{ $aktivitas->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <div>
                    @if($aktivitas->status == 'selesai')
                    <span style="background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">AI SELESAI</span>
                    @else
                    <span style="background: #FEF3C7; color: #B45309; padding: 6px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">PROSES INPUT</span>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 32px 0; color: var(--ink-60);">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; margin: 0 auto 12px; color: var(--cream);">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" />
                    <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                <p style="font-size: 14px; font-weight: 500;">Belum ada aktivitas tes eksplorasi karier dari siswa saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection