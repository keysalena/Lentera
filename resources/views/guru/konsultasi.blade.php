@extends('layouts.guru')
@section('title', 'LENTERA - Ruang Konsultasi (Smart Triage)')

@section('dashboard_content')
<div style="animation: fadeIn 0.4s ease-in-out; padding-bottom: 40px;">

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(234, 88, 12, 0.1); color: #EA580C; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; margin-bottom: 8px;">
                <svg viewBox="0 0 24 24" fill="none" style="width: 14px; height: 14px;"><path d="M13 10V3L4 14H11V21L20 10H13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                SMART TRIAGE SYSTEM
            </div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Ruang Konsultasi BK</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola pengajuan berdasarkan urutan prioritas dan status jadwal.</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600; border: 1px solid #34D399;">
        {{ session('success') }}
    </div>
    @endif

    <div style="display: flex; gap: 8px; border-bottom: 2px solid var(--cream); margin-bottom: 24px; overflow-x: auto;">
        <button class="tab-btn active" onclick="filterKonsultasi('Menunggu', this)">
            🚨 Menunggu Jadwal
        </button>
        <button class="tab-btn" onclick="filterKonsultasi('Dijadwalkan', this)">
            📅 Jadwal Aktif
        </button>
        <button class="tab-btn" onclick="filterKonsultasi('Selesai', this)">
            ✅ Riwayat Selesai
        </button>
        <button class="tab-btn" onclick="filterKonsultasi('Semua', this)" style="margin-left: auto;">
            Lihat Semua
        </button>
    </div>

    <div id="konsultasi-container" style="display: flex; flex-direction: column; gap: 16px;">
        @forelse($konsultasi as $k)
            @php
                
                $borderColor = '#10B981'; 
                $badgeBg = '#D1FAE5'; $badgeText = '#065F46';
                
                if($k->tingkat_prioritas == 'Tinggi') {
                    $borderColor = '#EF4444'; 
                    $badgeBg = '#FEE2E2'; $badgeText = '#B91C1C';
                } elseif($k->tingkat_prioritas == 'Menengah') {
                    $borderColor = '#F59E0B'; 
                    $badgeBg = '#FEF3C7'; $badgeText = '#B45309';
                }

                $opacity = $k->status == 'Selesai' ? '0.7' : '1';
            @endphp

            <div class="konsultasi-card" data-status="{{ $k->status }}" style="background: var(--white); border: 1px solid rgba(171, 168, 159, 0.25); border-left: 4px solid {{ $borderColor }}; border-radius: 12px; padding: 24px; opacity: {{ $opacity }}; box-shadow: 0 4px 12px rgba(0,0,0,0.02); display: {{ $k->status == 'Menunggu' ? 'block' : 'none' }};">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 24px;">
                    
                    <div style="flex: 1; min-width: 300px;">
                        <div style="display: flex; gap: 8px; margin-bottom: 12px; align-items: center;">
                            <span style="background: {{ $badgeBg }}; color: {{ $badgeText }}; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; letter-spacing: 0.05em;">
                                PRIORITAS: {{ strtoupper($k->tingkat_prioritas) }}
                            </span>
                            <span style="font-size: 11px; font-weight: 700; color: var(--ink-60); background: var(--paper); padding: 4px 10px; border-radius: 99px;">
                                STATUS: {{ strtoupper($k->status) }}
                            </span>
                        </div>
                        <a href="{{ route('guru.siswa.detail', $k->akun_siswa->id ?? '') }}" style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 4px; text-decoration: none;">
                            {{ optional($k->akun_siswa)->nama ?? 'Siswa Tidak Diketahui' }}
                        </a>
                        <p style="font-size: 14px; color: var(--ink); margin-top: 8px; margin-bottom: 0;">
                            <strong>Topik:</strong> {{ $k->topik }}
                        </p>
                        <p style="font-size: 13px; color: var(--ink-60); margin-top: 4px; line-height: 1.5;">
                            "{{ $k->alasan_siswa }}"
                        </p>
                    </div>

                    <div style="background: var(--paper); padding: 16px; border-radius: 12px; border: 1px solid var(--cream); width: 340px;">
                        
                        @if($k->status == 'Menunggu')
                            <div style="font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Tentukan Jadwal Tatap Muka:</div>
                            <form action="{{ route('guru.konsultasi.jadwal', $k->id_konsultasi) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                @csrf @method('PUT')
                                <input type="datetime-local" name="jadwal_konsultasi" required style="padding: 10px; border: 1px solid var(--ink-30); border-radius: 8px; font-size: 13px; outline: none; background: var(--white);">
                                <button type="submit" style="background: var(--amber); color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Terima & Kirim Jadwal</button>
                            </form>

                        @elseif($k->status == 'Dijadwalkan')
                            <div style="font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Jadwal Aktif:</div>
                            <div style="font-size: 15px; font-weight: 800; color: var(--amber); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                                <svg viewBox="0 0 24 24" fill="none" style="width: 18px; height: 18px; color: var(--amber);"><path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                {{ $k->jadwal_konsultasi ? \Carbon\Carbon::parse($k->jadwal_konsultasi)->format('d M Y, H:i') : '-' }} WIB
                            </div>
                            
                            <form action="{{ route('guru.konsultasi.selesai', $k->id_konsultasi) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                @csrf @method('PUT')
                                <textarea name="catatan_guru" rows="2" required placeholder="Tulis ringkasan hasil konseling..." style="padding: 10px; border: 1px solid var(--ink-30); border-radius: 8px; font-size: 13px; outline: none; resize: vertical; background: var(--white);"></textarea>
                                <button type="submit" style="background: #10B981; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">Tandai Selesai</button>
                            </form>

                        @elseif($k->status == 'Selesai')
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: #10B981; margin-bottom: 8px;">
                                <svg viewBox="0 0 24 24" fill="none" style="width: 16px; height: 16px;"><path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                KONSULTASI SELESAI
                            </div>
                            <div style="font-size: 13px; color: var(--ink); background: var(--white); padding: 12px; border-radius: 8px; border: 1px solid rgba(171, 168, 159, 0.25);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--ink-60); display: block; margin-bottom: 4px;">Catatan BK:</span>
                                {{ $k->catatan_guru }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div id="empty-state" style="background: var(--paper); border: 1px dashed var(--ink-30); padding: 48px; text-align: center; border-radius: 16px;">
                <p style="color: var(--ink-60); font-weight: 600; margin: 0;">Belum ada pengajuan konsultasi dari siswa.</p>
            </div>
        @endforelse

        <div id="filter-empty-state" style="display: none; background: var(--paper); border: 1px dashed var(--ink-30); padding: 48px; text-align: center; border-radius: 16px;">
            <p style="color: var(--ink-60); font-weight: 600; margin: 0;">Tidak ada konsultasi pada tab ini.</p>
        </div>
    </div>
</div>

<style>
    /* Styling untuk Tab Button */
    .tab-btn {
        padding: 12px 20px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--ink-60);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .tab-btn:hover {
        color: var(--amber);
        background: rgba(201, 123, 42, 0.05);
        border-radius: 8px 8px 0 0;
    }
    .tab-btn.active {
        color: var(--amber);
        border-bottom-color: var(--amber);
    }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function filterKonsultasi(statusTarget, btnElement) {
        
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');

        
        const cards = document.querySelectorAll('.konsultasi-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            
            if (statusTarget === 'Semua' || cardStatus === statusTarget) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        
        const filterEmptyState = document.getElementById('filter-empty-state');
        if (visibleCount === 0 && cards.length > 0) {
            filterEmptyState.style.display = 'block';
        } else {
            filterEmptyState.style.display = 'none';
        }
    }

    
    document.addEventListener("DOMContentLoaded", function() {
        const firstTab = document.querySelector('.tab-btn');
        if(firstTab) {
            filterKonsultasi('Menunggu', firstTab);
        }
    });
</script>
@endsection