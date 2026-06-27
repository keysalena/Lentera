@extends('layouts.guru')

@section('dashboard_content')
<div class="guru-siswa" style="animation: fadeIn 0.4s ease-in-out;">

    <!-- ── HEADER & FILTER BAR ── -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Daftar Siswa</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola dan pantau seluruh data eksplorasi siswa.</p>
        </div>

        <!-- Form Pencarian -->
        <form action="{{ route('guru.siswa') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
            <!-- Kolom Pencarian -->
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama siswa..."
                style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 260px; outline: none; font-size: 14px;">
            <button type="submit" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Cari</button>
        </form>
    </div>

    <!-- ── TABEL DATA SISWA ── -->
    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA SISWA</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">TAHUN MASUK</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">STATUS DATA</th>
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
                        {{ $siswa->siswa->angkatan }}
                    </td>
                    <td style="padding: 20px 24px;">
                        <!-- Logika sederhana: Jika NISN masih pakai 'S' (default saat daftar), berarti belum lengkap -->
                        @if(Str::startsWith(optional($siswa->siswa)->nisn, 'S'))
                        <span style="background: #FFF7ED; color: #EA580C; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">PROGRES</span>
                        @else
                        <span style="background: #E0F2FE; color: #0284C7; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">LENGKAP</span>
                        @endif
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 16px;">
                            <a href="{{ route('guru.siswa.detail', $siswa->id) }}" style="background: var(--amber); color: var(--white); padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
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