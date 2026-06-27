@extends('layouts.guru')

@section('dashboard_content')
<div class="guru-dominasi" style="animation: fadeIn 0.4s ease-in-out;">

    <!-- ── TOMBOL KEMBALI ── -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('guru.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--ink-60); text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='var(--amber)';" onmouseout="this.style.color='var(--ink-60)';">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Ringkasan
        </a>
    </div>

    <!-- ── HEADER & FILTER BAR ── -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Dominansi Bidang</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Rincian bidang/jurusan hasil rekomendasi AI untuk setiap siswa yang telah menyelesaikan tes.</p>
        </div>

        <!-- Form Pencarian -->
        <form action="{{ route('guru.dominasi') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama siswa..."
                style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 260px; outline: none; font-size: 14px;">
            <button type="submit" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Cari</button>
        </form>
    </div>

    <!-- ── TABEL DATA DOMINASI BIDANG ── -->
    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA SISWA</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">TAHUN MASUK</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">BIDANG REKOMENDASI</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; color: var(--ink-60);">TINDAKAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eksplorasis as $item)
                <tr style="border-top: 1px solid var(--cream);">
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 700; color: var(--ink);">{{ $item['nama'] }}</div>
                        <div style="font-size: 12px; color: var(--ink-60);">{{ $item['email'] }}</div>
                    </td>
                    <td style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ $item['angkatan'] }}
                    </td>
                    <td style="padding: 20px 24px;">
                        <span style="background: var(--amber-bg, #FFF7ED); color: var(--amber); padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                            {{ $item['bidang'] }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 16px;">
                            @if($item['id_user'])
                            <a href="{{ route('guru.siswa.detail', $item['id_user']) }}" style="background: var(--amber); color: var(--white); padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                        Belum ada siswa yang menyelesaikan tes eksplorasi karier.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection