@extends('layouts.guru')

@section('dashboard_content')
<div class="guru-dominasi" style="animation: fadeIn 0.4s ease-in-out;">

    
    <div style="margin-bottom: 24px;">
        <a href="{{ route('guru.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--ink-60); text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='var(--amber)';" onmouseout="this.style.color='var(--ink-60)';">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Kembali ke Ringkasan
        </a>
    </div>

    <div style="margin-bottom: 24px;">
        <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Dominansi Bidang</h2>
        <p style="font-size: 14px; color: var(--ink-60);">Jumlah siswa per bidang hasil rekomendasi AI, serta daftar lengkap siswa di sekolah Anda.</p>
    </div>

    
    
    
    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); padding: 32px; margin-bottom: 32px;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 24px;">Jumlah Siswa per Bidang</h3>

        @if(count($rekapBidang) > 0)
        @php $maxJumlah = max($rekapBidang); @endphp

        <div style="display: flex; flex-direction: column; gap: 18px;">
            @foreach($rekapBidang as $nama_bidang => $jumlah)
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span style="font-size: 13px; font-weight: 600; color: var(--ink);">{{ $nama_bidang }}</span>
                    <span style="font-size: 13px; font-weight: 700; color: var(--amber);">{{ $jumlah }} siswa</span>
                </div>
                <div style="background: var(--cream); border-radius: 99px; height: 14px; width: 100%; overflow: hidden;">
                    <div style="background: var(--amber); height: 100%; border-radius: 99px; width: {{ $maxJumlah > 0 ? ($jumlah / $maxJumlah) * 100 : 0 }}%; transition: width 0.4s ease-in-out;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 32px 0; color: var(--ink-60);">
            <p style="font-size: 14px; font-weight: 500;">Belum ada siswa yang menyelesaikan tes eksplorasi karier.</p>
        </div>
        @endif
    </div>

    
    
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Daftar Siswa</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola dan pantau seluruh data eksplorasi siswa.</p>
        </div>

        
        <form action="{{ route('guru.dominasi') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
            
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama siswa..."
                style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 260px; outline: none; font-size: 14px;">
            <button type="submit" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Cari</button>
        </form>
    </div>

    
    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA SISWA</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">TAHUN MASUK</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">REKOMENDASI AI</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; color: var(--ink-60);">TINDAKAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                <tr style="border-top: 1px solid var(--cream);">
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 700; color: var(--ink);">{{ $siswa->nama }}</div>
                        <div style="font-size: 12px; color: var(--ink-60);">{{ $siswa->email }}</div>
                    </td>
                    <td style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ optional($siswa->siswa_data)->angkatan ?? '-' }}
                    </td>
                    <td style="padding: 20px 24px;">
                        @if($siswa->bidang_ai == '-')
                        <span style="background: #FFF7ED; color: #EA580C; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">BELUM TES</span>
                        @else
                        <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 700;">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;">
                                <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ $siswa->bidang_ai }}
                        </div>
                        @endif
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 16px;">
                            <a href="{{ route('guru.siswa.detail', $siswa->id) }}" style="background: var(--amber); color: var(--white); padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Lihat Laporan</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 13px; color: var(--ink-60);">
            Menampilkan {{ $siswas->firstItem() ?? 0 }} hingga {{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} siswa
        </div>
        <div>
            {{ $siswas->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection