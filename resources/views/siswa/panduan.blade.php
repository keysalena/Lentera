@extends('layouts.siswa')

@section('dashboard_content')
<div class="guide-page">

    <div class="guide-header">
        <span class="guide-eyebrow">Panduan</span>
        <h1 class="guide-title">Petunjuk Penggunaan Aplikasi LENTERA</h1>
        <p class="guide-sub">
            Ikuti 5 langkah berikut secara berurutan agar hasil analisis AI kamu akurat.
        </p>
    </div>

    <div class="guide-steps">

        {{-- LANGKAH 1: RIASEC --}}
        <div class="guide-step">
            <span class="guide-step__num">01</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 6h11M9 12h11M9 18h11"/>
                    <path d="M4 6h.01M4 12h.01M4 18h.01"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Mengisi Instrumen RIASEC</h3>
                <p>
                    Jawablah seluruh pernyataan kuesioner minat pada menu
                    <b>Input Eksplorasi</b> dengan jujur, sesuai minat, kemampuan,
                    dan citra diri kamu saat ini.
                </p>
            </div>
        </div>

        {{-- LANGKAH 2: NILAI AKADEMIK --}}
        <div class="guide-step">
            <span class="guide-step__num">02</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 3h7l5 5v13H7z"/>
                    <path d="M14 3v5h5"/>
                    <path d="M9.5 13h5M9.5 17h5"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <div class="guide-step__title-row">
                    <h3>Mengisi Nilai Akademik</h3>
                    
                </div>
                <p>Masukkan nilai rapor kamu pada kolom mata pelajaran yang tersedia.</p>
                <div class="guide-note">
                    Tidak mengambil mata pelajaran tertentu (misal Kimia atau Biologi)?
                    Biarkan kosong atau isi dengan angka <b>0</b>.
                </div>
            </div>
        </div>

        {{-- LANGKAH 3: UPLOAD TULISAN TANGAN --}}
        <div class="guide-step">
            <span class="guide-step__num">03</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 8h3l1.5-2h7L17 8h3a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                    <circle cx="12" cy="13.5" r="3.2"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Mengunggah Foto Tulisan Tangan</h3>
                <p>
                    Siapkan kertas kosong, tuliskan cita-cita atau hobi kamu minimal
                    5 baris. Foto tulisan tersebut dengan jelas dan pencahayaan cukup,
                    lalu unggah pada menu <b>Input Eksplorasi</b>.
                </p>
                <ul class="guide-checklist">
                    <li>Gunakan alas kertas polos, tanpa garis.</li>
                    <li>Pastikan foto tidak blur dan tidak terpotong.</li>
                    <li>Ambil foto tegak lurus dari atas kertas.</li>
                </ul>
            </div>
        </div>

        {{-- LANGKAH 4: PROSES ANALISIS AI --}}
        <div class="guide-step">
            <span class="guide-step__num">04</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="6" y="6" width="12" height="12" rx="2.5"/>
                    <path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.5 4.5l2 2M17.5 17.5l2 2M4.5 19.5l2-2M17.5 6.5l2-2"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Memproses Analisis AI</h3>
                <p>
                    Setelah semua data terisi, klik tombol
                    <b>Finalisasi &amp; Analisis AI</b>. Sistem akan mengirim data kamu
                    ke <i>Machine Learning</i> untuk diproses. Hasil prediksi (Karakter,
                    Rekomendasi Jurusan, dan Prediksi IPK) akan muncul di halaman
                    <b>Hasil Pemetaan</b>.
                </p>
            </div>
        </div>

        {{-- LANGKAH 5: KONSULTASI KARIER --}}
        <div class="guide-step">
            <span class="guide-step__num">05</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5h16v10H9l-4 4V5z"/>
                    <path d="M8 9h8M8 12h5"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <div class="guide-step__title-row">
                    <h3>Konsultasi Karier dengan Guru BK</h3>
                    <span class="guide-pill">Opsional</span>
                </div>
                <p>
                    Kalau masih bingung dengan hasil rekomendasi AI, kamu bisa mengajukan
                    konsultasi langsung dengan Guru Bimbingan Konseling (BK) di sekolahmu
                    lewat menu <b>Konsultasi Karier</b>.
                </p>
                <div class="guide-note guide-note--warning">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="guide-note__icon">
                        <rect x="5" y="10" width="14" height="10" rx="2"/>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                    </svg>
                    <span>
                        Menu <b>Konsultasi Karier</b> di sidebar akan terkunci sampai kamu
                        memasukkan <b>kode lisensi</b> yang diberikan oleh Guru BK-mu
                        masing-masing. Minta kode tersebut ke Guru BK-mu, lalu masukkan pada
                        kolom yang tersedia agar menu ini terbuka.
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .guide-page {
        max-width: 860px;
    }

    .guide-header {
        margin-bottom: 40px;
    }

    .guide-eyebrow {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--amber);
        margin-bottom: 10px;
    }

    .guide-title {
        font-family: 'DM Serif Display', serif;
        font-size: 30px;
        letter-spacing: -0.02em;
        color: var(--ink);
        margin-bottom: 10px;
    }

    .guide-sub {
        font-size: 15px;
        line-height: 1.65;
        color: var(--ink-60);
        max-width: 520px;
    }

    .guide-steps {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .guide-step {
        position: relative;
        display: grid;
        grid-template-columns: 48px 1fr;
        align-items: start;
        column-gap: 20px;
        background: var(--white);
        border: 1px solid rgba(171, 168, 159, 0.22);
        border-radius: 16px;
        padding: 26px 28px;
    }

    .guide-step__num {
        position: absolute;
        top: 14px;
        right: 22px;
        font-family: 'DM Serif Display', serif;
        font-size: 32px;
        color: rgba(201, 123, 42, 0.16);
        line-height: 1;
        user-select: none;
    }

    .guide-step__icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--amber-bg);
        border: 1px solid #EDD19B;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .guide-step__icon svg {
        width: 21px;
        height: 21px;
        color: var(--amber);
    }

    .guide-step__body h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
        padding-right: 40px;
    }

    .guide-step__title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .guide-step__title-row h3 {
        margin-bottom: 0;
    }

    .guide-step__body p {
        font-size: 14px;
        line-height: 1.7;
        color: var(--ink-60);
        margin: 8px 0 0;
    }

    .guide-pill {
        display: inline-flex;
        align-items: center;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--amber);
        background: var(--amber-bg);
        border: 1px solid #EDD19B;
        border-radius: 999px;
        padding: 3px 11px;
    }

    .guide-note {
        margin-top: 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: var(--amber-bg);
        border: 1px solid #EDD19B;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 13.5px;
        line-height: 1.6;
        color: #8a5a1c;
    }

    .guide-note__icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        margin-top: 1px;
        color: var(--amber);
    }

    .guide-note--warning {
        background: #FDECEC;
        border-color: #F3B9B9;
        color: #9C3A3A;
    }

    .guide-note--warning .guide-note__icon {
        color: #C94848;
    }

    .guide-note--warning b {
        color: #7A2222;
    }

    .guide-checklist {
        margin: 14px 0 0;
        padding: 0;
        list-style: none;
    }

    .guide-checklist li {
        position: relative;
        padding-left: 24px;
        font-size: 13.5px;
        color: var(--ink-60);
        margin-bottom: 8px;
    }

    .guide-checklist li:last-child {
        margin-bottom: 0;
    }

    .guide-checklist li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--amber-bg);
        border: 1px solid #EDD19B;
    }

    .guide-checklist li::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 9px;
        width: 6px;
        height: 3px;
        border-left: 1.6px solid var(--amber);
        border-bottom: 1.6px solid var(--amber);
        transform: rotate(-45deg);
    }

    @media (max-width: 640px) {
        .guide-step {
            grid-template-columns: 40px 1fr;
            padding: 20px;
        }

        .guide-step__icon {
            width: 38px;
            height: 38px;
        }

        .guide-step__num {
            display: none;
        }
    }
</style>
@endsection