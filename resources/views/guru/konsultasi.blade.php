@extends('layouts.guru')
@section('title', 'LENTERA - Ruang Konsultasi (Smart Triage)')

@section('dashboard_content')
<div style="animation: fadeIn 0.4s ease-in-out; padding-bottom: 40px;">

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(234, 88, 12, 0.1); color: #EA580C; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; margin-bottom: 8px;">
                <svg viewBox="0 0 24 24" fill="none" style="width: 14px; height: 14px;"><path d="M13 10V3L4 14H11V21L20 10H13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                SMART TRIAGE SYSTEM
            </div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Ruang Konsultasi BK</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Daftar pengajuan telah diurutkan otomatis berdasarkan tingkat urgensi siswa.</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600;">
        {{ session('success') }}
    </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 16px;">
        @forelse($konsultasi as $k)
            @php
                // Tentukan warna border berdasarkan prioritas
                $borderColor = '#10B981'; // Default Hijau (Rendah)
                $badgeBg = '#D1FAE5'; $badgeText = '#065F46';
                
                if($k->tingkat_prioritas == 'Tinggi') {
                    $borderColor = '#EF4444'; // Merah
                    $badgeBg = '#FEE2E2'; $badgeText = '#B91C1C';
                } elseif($k->tingkat_prioritas == 'Menengah') {
                    $borderColor = '#F59E0B'; // Kuning
                    $badgeBg = '#FEF3C7'; $badgeText = '#B45309';
                }

                // Efek redup jika sudah selesai
                $opacity = $k->status == 'Selesai' ? '0.6' : '1';
            @endphp

            <div style="background: var(--white); border: 1px solid rgba(171, 168, 159, 0.25); border-left: 4px solid {{ $borderColor }}; border-radius: 12px; padding: 24px; opacity: {{ $opacity }}; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                    
                    <div style="flex: 1; min-width: 300px;">
                        <div style="display: flex; gap: 8px; margin-bottom: 12px; align-items: center;">
                            <span style="background: {{ $badgeBg }}; color: {{ $badgeText }}; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; letter-spacing: 0.05em;">
                                PRIORITAS: {{ strtoupper($k->tingkat_prioritas) }}
                            </span>
                            <span style="font-size: 11px; font-weight: 700; color: var(--ink-60); background: var(--paper); padding: 4px 10px; border-radius: 99px;">
                                STATUS: {{ strtoupper($k->status) }}
                            </span>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">
                            {{ optional($k->akun_siswa)->nama ?? 'Siswa Tidak Diketahui' }}
                        </h3>
                        <p style="font-size: 13px; color: var(--ink-60); margin-bottom: 12px;">
                            <strong>Keluhan:</strong> {{ $k->topik }} <br>
                            "{{ $k->alasan_siswa }}"
                        </p>
                    </div>

                    <div style="background: var(--paper); padding: 16px; border-radius: 12px; border: 1px solid var(--cream); width: 320px;">
                        
                        @if($k->status == 'Menunggu')
                            <div style="font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Tentukan Jadwal Tatap Muka:</div>
                            <form action="{{ route('guru.konsultasi.jadwal', $k->id_konsultasi) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                @csrf @method('PUT')
                                <input type="datetime-local" name="jadwal_konsultasi" required style="padding: 10px; border: 1px solid var(--ink-30); border-radius: 8px; font-size: 13px; outline: none;">
                                <button type="submit" style="background: var(--amber); color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">Terima & Kirim Jadwal</button>
                            </form>

                        @elseif($k->status == 'Dijadwalkan')
                            <div style="font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Jadwal Aktif:</div>
                            <div style="font-size: 14px; font-weight: 800; color: var(--amber); margin-bottom: 16px;">
                                {{ $k->jadwal_konsultasi ? $k->jadwal_konsultasi->format('d M Y, H:i') : '-' }} WIB
                            </div>
                            
                            <form action="{{ route('guru.konsultasi.selesai', $k->id_konsultasi) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                @csrf @method('PUT')
                                <textarea name="catatan_guru" rows="2" required placeholder="Tulis ringkasan hasil konseling di sini..." style="padding: 10px; border: 1px solid var(--ink-30); border-radius: 8px; font-size: 13px; outline: none; resize: vertical;"></textarea>
                                <button type="submit" style="background: #10B981; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">Tandai Selesai</button>
                            </form>

                        @elseif($k->status == 'Selesai')
                            <div style="font-size: 12px; font-weight: 700; color: #10B981; margin-bottom: 4px;">KONSULTASI SELESAI</div>
                            <div style="font-size: 12px; color: var(--ink-60);">
                                <strong>Catatan BK:</strong><br>
                                {{ $k->catatan_guru }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div style="background: var(--paper); border: 1px dashed var(--ink-30); padding: 48px; text-align: center; border-radius: 16px;">
                <p style="color: var(--ink-60); font-weight: 600;">Belum ada pengajuan konsultasi dari siswa.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection