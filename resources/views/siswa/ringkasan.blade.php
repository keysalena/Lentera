@extends('layouts.siswa') 
@section('title', 'LENTERA - Ringkasan Dasbor')

@section('dashboard_content')
<div class="dashboard-summary" style="animation: fadeIn 0.4s ease-in-out;">
    
    <div style="margin-bottom: 32px;">
        <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
            Halo, {{ Auth::user()->nama }}!
        </h2>
        <p style="font-size: 15px; color: var(--ink-60);">
            Selamat datang di portal eksplorasi LENTERA. Pantau progres pengisian data dan temukan rekomendasi karier terbaikmu di sini.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin-bottom: 40px;">
        
        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); display: flex; flex-direction: column; gap: 16px; box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="width: 48px; height: 48px; background: {{ $hasNilai ? '#F0FDF4' : 'var(--amber-bg)' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: {{ $hasNilai ? '#10B981' : 'var(--amber)' }};">
                    @if($hasNilai)
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path d="M12 14L22 9L12 4L2 9L12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 9V15C22 15 12 20 12 20C12 20 2 15 2 15V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @endif
                </div>
                @if($hasNilai)
                    <span style="font-size: 12px; font-weight: 700; color: #10B981; background: #F0FDF4; padding: 4px 10px; border-radius: 99px;">Tersimpan</span>
                @else
                    <span style="font-size: 12px; font-weight: 700; color: #BA1A1A; background: #FFF5F5; padding: 4px 10px; border-radius: 99px;">Belum Lengkap</span>
                @endif
            </div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Data Akademik</h3>
                <p style="font-size: 13px; color: var(--ink-60);">Nilai mata pelajaran (Matematika, Fisika, Informatika, dll).</p>
            </div>
        </div>

        <div style="background: var(--white); padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); display: flex; flex-direction: column; gap: 16px; box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="width: 48px; height: 48px; background: {{ $hasGambar ? '#F0FDF4' : 'var(--amber-bg)' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: {{ $hasGambar ? '#10B981' : 'var(--amber)' }};">
                    @if($hasGambar)
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @endif
                </div>
                @if($hasGambar)
                    <span style="font-size: 12px; font-weight: 700; color: #10B981; background: #F0FDF4; padding: 4px 10px; border-radius: 99px;">Telah Diunggah</span>
                @else
                    <span style="font-size: 12px; font-weight: 700; color: #BA1A1A; background: #FFF5F5; padding: 4px 10px; border-radius: 99px;">Belum Diunggah</span>
                @endif
            </div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Tulisan Reflektif</h3>
                <p style="font-size: 13px; color: var(--ink-60);">Foto tulisan tangan narasi bebas sebagai bahan ekstraksi AI.</p>
            </div>
        </div>

        <div style="background: {{ $statusEksplorasi == 'selesai' ? 'var(--white)' : 'var(--cream)' }}; padding: 24px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; 
                    background: {{ $statusEksplorasi == 'selesai' ? '#F0FDF4' : 'var(--white)' }}; 
                    color: {{ $statusEksplorasi == 'selesai' ? '#10B981' : 'var(--ink-60)' }};">
                    @if($statusEksplorasi == 'selesai')
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @endif
                </div>
                
                @if($statusEksplorasi == 'selesai')
                    <span style="font-size: 12px; font-weight: 700; color: #10B981; background: #F0FDF4; padding: 4px 10px; border-radius: 99px;">Selesai</span>
                @elseif($statusEksplorasi == 'proses')
                    <span style="font-size: 12px; font-weight: 700; color: var(--amber); background: var(--amber-bg); padding: 4px 10px; border-radius: 99px;">Dalam Proses AI</span>
                @else
                    <span style="font-size: 12px; font-weight: 700; color: var(--ink-60); background: var(--paper); border: 1px solid var(--ink-30); padding: 4px 10px; border-radius: 99px;">Menunggu Data</span>
                @endif
            </div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Hasil & Rekomendasi</h3>
                <p style="font-size: 13px; color: var(--ink-60);">Pemrosesan data menggunakan model Machine Learning.</p>
            </div>
        </div>

    </div>

    <div style="background: var(--ink); border-radius: 20px; padding: 40px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; position: relative; overflow: hidden;">
        
        <div style="position: absolute; right: -20px; top: -40px; width: 160px; height: 160px; border-radius: 50%; border: 20px solid rgba(245, 158, 11, 0.1);"></div>
        <div style="position: absolute; right: 80px; bottom: -60px; width: 120px; height: 120px; border-radius: 50%; border: 15px solid rgba(245, 158, 11, 0.05);"></div>

        <div style="position: relative; z-index: 10;">
            @if($statusEksplorasi == 'selesai')
                <div style="display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #10B981; margin-bottom: 12px; background: rgba(16, 185, 129, 0.15); padding: 4px 10px; border-radius: 6px;">
                    Analisis Berhasil
                </div>
                <h3 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--white); margin-bottom: 8px;">
                    Eksplorasi Anda Telah Diproses!
                </h3>
                <p style="font-size: 14px; color: rgba(255, 255, 255, 0.7); max-width: 480px;">
                    Sistem telah selesai memetakan potensi kepribadian dan memberikan rekomendasi jurusan untuk Anda. Silakan lihat hasil selengkapnya.
                </p>
            @else
                <div style="display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--amber-lt); margin-bottom: 12px; background: rgba(245, 192, 122, 0.15); padding: 4px 10px; border-radius: 6px;">
                    Tugas Saat Ini
                </div>
                <h3 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--white); margin-bottom: 8px;">
                    @if($hasGambar || $hasNilai)
                        Lanjutkan Pengisian Data
                    @else
                        Lengkapi Data Eksplorasi Anda
                    @endif
                </h3>
                <p style="font-size: 14px; color: rgba(255, 255, 255, 0.7); max-width: 480px;">
                    @if($hasGambar || $hasNilai)
                        Anda memiliki draf pengisian yang belum selesai dikirim. Lanjutkan tahap pengisian hingga melakukan finalisasi akhir.
                    @else
                        Sistem LENTERA belum dapat memetakan potensi Anda. Silakan ke menu Input Eksplorasi untuk memasukkan nilai dan mengunggah tulisan Anda.
                    @endif
                </p>
            @endif
        </div>

        <div style="position: relative; z-index: 10;">
            @if($statusEksplorasi == 'selesai')
                <a href="{{ route('siswa.hasil') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #10B981; color: var(--white); font-size: 15px; font-weight: 700; padding: 14px 28px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, opacity 0.2s;" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)';" onmouseout="this.style.opacity='1'; this.style.transform='none';">
                    Lihat Hasil Analisis
                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            @else
                <a href="{{ route('siswa.input') }}" style="display: inline-flex; align-items: center; gap: 8px; background: var(--amber); color: var(--white); font-size: 15px; font-weight: 700; padding: 14px 28px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, opacity 0.2s;" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)';" onmouseout="this.style.opacity='1'; this.style.transform='none';">
                    @if($hasGambar || $hasNilai) Lanjutkan @else Mulai Isi Data @endif
                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            @endif
        </div>
    </div>

</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection