@extends('layouts.siswa')
@section('title', 'LENTERA - Konsultasi Karier')

@section('dashboard_content')
<div style="animation: fadeIn 0.4s ease-in-out; padding-bottom: 40px;">

    <div style="margin-bottom: 32px;">
        <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink); margin-bottom: 8px;">Konsultasi Karier</h2>
        <p style="font-size: 14px; color: var(--ink-60); max-width: 600px;">
            Diskusikan hasil analisis AI dan kebingunganmu terkait pilihan program studi langsung dengan Guru Bimbingan Konseling (BK) di sekolahmu.
        </p>
    </div>

    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 12px;">
        <svg viewBox="0 0 24 24" fill="none" style="width: 20px; height: 20px;">
            <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($konsultasiAktif)
    <div style="background: var(--ink); color: var(--white); padding: 32px; border-radius: 20px; margin-bottom: 32px; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -20px; top: -40px; width: 140px; height: 140px; border-radius: 50%; border: 20px solid rgba(255,255,255,0.05);"></div>

        <div style="position: relative; z-index: 10;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.1); padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; margin-bottom: 16px;">
                STATUS PENGAJUAN
            </div>

            @if($konsultasiAktif->status == 'Menunggu')
            <h3 style="font-family: 'DM Serif Display', serif; font-size: 24px; margin-bottom: 8px;">Menunggu Konfirmasi Jadwal</h3>
            <p style="font-size: 14px; color: rgba(255,255,255,0.8); max-width: 500px;">
                Pengajuan konsultasi dengan topik <strong>"{{ $konsultasiAktif->topik }}"</strong> telah diterima. Guru BK sedang meninjau datamu dan akan segera menentukan jadwal pertemuan. Silakan cek halaman ini secara berkala.
            </p>
            @elseif($konsultasiAktif->status == 'Dijadwalkan')
            <h3 style="font-family: 'DM Serif Display', serif; font-size: 24px; margin-bottom: 8px; color: var(--amber);">Jadwal Konsultasi Telah Ditetapkan!</h3>
            <p style="font-size: 14px; color: rgba(255,255,255,0.8); max-width: 500px; margin-bottom: 24px;">
                Guru BK telah menyetujui pengajuanmu. Harap hadir tepat waktu sesuai dengan jadwal di bawah ini:
            </p>
            <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 16px; display: inline-block;">
                <div style="font-size: 12px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Waktu & Tanggal</div>
                <div style="font-size: 18px; font-weight: 700;">
                    {{ $konsultasiAktif->jadwal_konsultasi ? $konsultasiAktif->jadwal_konsultasi->translatedFormat('l, d F Y | H:i') . ' WIB' : 'Belum diset' }}
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div style="display: grid; grid-template-columns: {{ (!$konsultasiAktif && $eksplorasi) ? '1.5fr 1fr' : '1fr' }}; gap: 24px;">

        @if(!$konsultasiAktif && $eksplorasi)
        <div style="background: var(--white); border: 1px solid rgba(171, 168, 159, 0.25); border-radius: 16px; padding: 32px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 24px;">Buat Pengajuan Baru</h3>

            <form action="{{ route('siswa.konsultasi.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf
                <input type="hidden" name="id_eksplorasi" value="{{ $eksplorasi->id_eksplorasi }}">

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Pilih Guru Bimbingan Konseling</label>
                    <select name="id_guru" required style="width: 100%; padding: 14px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; outline: none; background: var(--paper);">
                        <option value="">-- Pilih Guru BK Anda --</option>
                        @foreach($daftarGuru as $guruBK)
                        <option value="{{ $guruBK->id_guru }}">Bpk/Ibu {{ optional($guruBK->akun)->nama ?? 'Guru BK' }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Topik Pembahasan</label>
                    <select name="topik" required style="width: 100%; padding: 14px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; outline: none; background: var(--paper);">
                        <option value="">-- Pilih Topik --</option>
                        <option value="Bingung dengan hasil rekomendasi AI">Saya bingung dengan hasil rekomendasi AI</option>
                        <option value="Hasil berbeda dengan ekspektasi orang tua">Hasil berbeda dengan ekspektasi orang tua</option>
                        <option value="Ingin informasi lebih detail tentang jurusan/kampus">Ingin informasi lebih detail tentang jurusan/kampus</option>
                        <option value="Lainnya">Lainnya...</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Jelaskan kebingunganmu secara singkat</label>
                    <textarea name="alasan_siswa" rows="4" required placeholder="Contoh: AI merekomendasikan saya ke IT, tapi orang tua saya ingin saya masuk Kedokteran. Saya bingung harus bagaimana..." style="width: 100%; padding: 14px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; outline: none; resize: vertical; background: var(--paper);"></textarea>
                </div>

                <button type="submit" style="background: var(--amber); color: var(--white); border: none; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s; margin-top: 8px;">
                    Kirim Pengajuan Konsultasi
                </button>
            </form>
        </div>
        @elseif(!$konsultasiAktif && !$eksplorasi)
        <div style="background: var(--paper); border: 1px dashed var(--ink-30); border-radius: 16px; padding: 48px 32px; text-align: center;">
            <svg viewBox="0 0 24 24" fill="none" style="width: 48px; height: 48px; color: var(--ink-30); margin: 0 auto 16px;">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Selesaikan Eksplorasi Terlebih Dahulu</h3>
            <p style="font-size: 14px; color: var(--ink-60); max-width: 400px; margin: 0 auto 24px;">
                Kamu belum bisa mengajukan konsultasi karena sistem AI LENTERA belum memiliki data profilmu. Silakan input nilai dan tulisan tanganmu terlebih dahulu.
            </p>
            <a href="{{ route('siswa.input') }}" style="display: inline-block; background: var(--amber); color: white; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">Pergi ke Halaman Input</a>
        </div>
        @endif

        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 16px;">Riwayat & Catatan Guru BK</h3>

            @forelse($riwayatKonsultasi as $riwayat)
            <div style="background: var(--white); border: 1px solid rgba(171, 168, 159, 0.25); border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <div style="font-size: 11px; color: var(--ink-60); margin-bottom: 4px;">{{ $riwayat->updated_at->format('d M Y') }}</div>
                        <div style="font-size: 14px; font-weight: 700; color: var(--ink);">{{ $riwayat->topik }}</div>
                    </div>
                    <span style="background: var(--cream); color: var(--ink); font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">SELESAI</span>
                </div>
                <div style="background: var(--paper); border-left: 3px solid var(--amber); padding: 12px 16px; border-radius: 0 8px 8px 0;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--amber); margin-bottom: 4px; text-transform: uppercase;">Catatan Guru BK:</div>
                    <p style="font-size: 13px; color: var(--ink-60); margin: 0; line-height: 1.5;">
                        {{ $riwayat->catatan_guru ?? 'Tidak ada catatan khusus.' }}
                    </p>
                </div>
            </div>
            @empty
            <div style="background: var(--white); border: 1px dashed var(--ink-30); border-radius: 12px; padding: 32px 20px; text-align: center;">
                <p style="font-size: 13px; color: var(--ink-60); margin: 0;">Belum ada riwayat konsultasi yang selesai.</p>
            </div>
            @endforelse
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