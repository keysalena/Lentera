@extends('layouts.guru')
@section('title', 'LENTERA - Panduan Guru BK')

@section('dashboard_content')
<div class="guide-page guide-page--wide">

    <div class="guide-header">
        <span class="guide-eyebrow">Panduan Guru BK</span>
        <h1 class="guide-title">Panduan Operasional Guru Bimbingan Konseling</h1>
    </div>

    <div class="guide-steps">

        {{-- 01 — DASHBOARD & KODE REGISTRASI --}}
        <div class="guide-step">
            <span class="guide-step__num">01</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 3.5a3.5 3.5 0 1 1 4.95 4.95L9.5 18.4 5 19.5l1.1-4.5 8.4-9.5z"/>
                    <path d="M9 15l-3-3"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Dashboard Utama &amp; Pengelolaan Akses Siswa</h3>
                <p>
                    Setelah <i>login</i>, halaman pertama yang Anda lihat adalah
                    <b>Dashboard Monitoring BK</b>. Langkah pertama dan paling krusial
                    sebelum memulai bimbingan adalah memastikan siswa Anda terhubung ke
                    ruang lingkup pantauan Anda.
                </p>

                <div class="guide-codebox">
                    <div class="guide-codebox__top">
                        <span class="guide-codebox__label">Kode Registrasi Siswa</span>
                        <span class="guide-codebox__copy">Salin</span>
                    </div>
                    <div class="guide-codebox__value">S-XXX-XXX</div>
                </div>

                <ul class="guide-checklist">
                    <li>
                        <b>Fungsi kode:</b> siswa yang memasukkan kode ini di profil mereka
                        akan otomatis terhubung ke daftar pantauan Anda dan membuka menu
                        <b>Konsultasi Karier</b> pada aplikasi mereka.
                    </li>
                    <li>
                        <b>Cara membagikan:</b> klik tombol <b>Salin</b> di samping kode,
                        lalu bagikan ke siswa bimbingan Anda — misalnya lewat grup WhatsApp
                        kelas, pengumuman mading, atau saat tatap muka di kelas.
                    </li>
                </ul>
            </div>
        </div>

        {{-- 02 — RUANG KONSULTASI --}}
        <div class="guide-step">
            <span class="guide-step__num">02</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5h16v10H9l-4 4V5z"/>
                    <path d="M12 8v3M12 13.5h.01"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Ruang Konsultasi</h3>
                <p>
                    LENTERA dilengkapi sistem cerdas yang otomatis
                    menyaring dan mengurutkan pengajuan konsultasi siswa berdasarkan
                    tingkat urgensinya, jadi Anda tidak perlu lagi menyusun jadwal secara
                    manual. Halaman ini dibagi menjadi 3 tab navigasi:
                </p>

                <div class="guide-subgrid">
                    <div class="guide-subcard">
                        <span class="guide-subcard__tag">Tab 1</span>
                        <h4>🚨 Menunggu Jadwal</h4>
                        <p>Daftar siswa yang baru mengajukan permohonan konseling.</p>
                        <div class="guide-priority">
                            <span class="guide-priority__dot guide-priority__dot--high"></span>Tinggi
                            <span class="guide-priority__dot guide-priority__dot--mid"></span>Menengah
                            <span class="guide-priority__dot guide-priority__dot--low"></span>Rendah
                        </div>
                        <ol>
                            <li>Baca keluhan &amp; alasan siswa pada kartu yang tersedia.</li>
                            <li>Isi tanggal &amp; jam di kolom <b>Tentukan Jadwal Tatap Muka</b>.</li>
                            <li>Klik <b>Terima &amp; Kirim Jadwal</b> — undangan kalender otomatis terkirim ke email siswa.</li>
                        </ol>
                    </div>

                    <div class="guide-subcard">
                        <span class="guide-subcard__tag">Tab 2</span>
                        <h4>📅 Jadwal Aktif</h4>
                        <p>Agenda konsultasi yang sudah Anda setujui dan sedang/akan berjalan.</p>
                        <ol>
                            <li>Laksanakan konseling tatap muka atau daring sesuai jadwal.</li>
                            <li>Setelah sesi selesai, isi kolom <b>Catatan Guru</b> dengan ringkasan &amp; saran tindak lanjut.</li>
                            <li>Klik <b>Tandai Selesai</b> — data akan diarsipkan otomatis.</li>
                        </ol>
                    </div>

                    <div class="guide-subcard">
                        <span class="guide-subcard__tag">Tab 3</span>
                        <h4>✅ Riwayat Selesai</h4>
                        <p>
                            Seluruh rekam jejak bimbingan karier yang telah selesai. Buka
                            kapan saja untuk mengevaluasi perkembangan siswa atau mengingat
                            kembali catatan bimbingan sebelumnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 03 — DETAIL PROFIL & REKOMENDASI AI --}}
        <div class="guide-step">
            <span class="guide-step__num">03</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="3.2"/>
                    <path d="M5.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Membaca Detail Profil &amp; Rekomendasi AI Siswa</h3>
                <p>
                    Klik nama siswa pada halaman Ruang Konsultasi, atau cari namanya di
                    menu <b>Daftar Siswa</b>, untuk membuka Halaman Detail Analisis dengan
                    tiga data komprehensif:
                </p>

                <div class="guide-subgrid guide-subgrid--3">
                    <div class="guide-minicard">
                        <h4>Informasi Diri &amp; Riwayat Akademik</h4>
                        <p>Data demografi siswa dan tren nilai rapor — mata pelajaran apa yang paling dikuasai bisa langsung terlihat.</p>
                    </div>
                    <div class="guide-minicard">
                        <h4>Profil Kepribadian (RIASEC)</h4>
                        <p>Visualisasi persentase tipe kepribadian (Realistic, Investigative, Artistic, Social, Enterprising, Conventional). Tipe paling dominan disorot otomatis.</p>
                    </div>
                    <div class="guide-minicard">
                        <h4>Rekomendasi Program Studi</h4>
                        <p>5 besar rekomendasi jurusan dari AI, lengkap Tingkat Kecocokan (%) dan alasan singkat kecocokannya dengan nilai akademik &amp; kepribadian siswa.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 04 — TIPS BIMBINGAN --}}
        <div class="guide-step">
            <span class="guide-step__num">04</span>
            <div class="guide-step__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18h6M10 21h4"/>
                    <path d="M12 3a6 6 0 0 0-3.6 10.8c.4.3.6.8.6 1.2v.5h6v-.5c0-.4.2-.9.6-1.2A6 6 0 0 0 12 3z"/>
                </svg>
            </div>
            <div class="guide-step__body">
                <h3>Tips untuk Proses Bimbingan yang Maksimal</h3>

                <p><b>Jadikan AI sebagai mitra, bukan keputusan final.</b> Rekomendasi jurusan dari LENTERA adalah alat bantu berbasis data, bukan vonis mutlak — gunakan sebagai pemantik diskusi yang bermakna saat konseling.</p>

                <div class="guide-quote">
                    “Ibu melihat AI LENTERA merekomendasikan kamu di jurusan Ilmu
                    Komunikasi karena nilai bahasamu bagus dan kepribadian Sosialmu
                    tinggi. Bagaimana pendapatmu tentang itu?”
                </div>

                <ul class="guide-checklist">
                    <li>
                        <b>Gunakan Filter Angkatan</b> pada menu Daftar Siswa untuk
                        memfokuskan perhatian pada siswa tingkat akhir yang sedang
                        mempersiapkan strategi pendaftaran SNBP/SNBT.
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<style>
    .guide-page {
        max-width: 860px;
    }

    .guide-page--wide {
        max-width: 980px;
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
        margin-bottom: 8px;
    }

    .guide-tagline {
        font-family: 'DM Serif Display', serif;
        font-style: italic;
        font-size: 15px;
        color: var(--amber);
        margin-bottom: 16px;
    }

    .guide-sub {
        font-size: 14.5px;
        line-height: 1.7;
        color: var(--ink-60);
        max-width: 680px;
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
        padding: 28px 30px;
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
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 10px;
        padding-right: 40px;
    }

    .guide-step__body p {
        font-size: 14px;
        line-height: 1.7;
        color: var(--ink-60);
        margin: 0 0 12px;
    }

    .guide-step__body p:last-child {
        margin-bottom: 0;
    }

    /* Checklist */
    .guide-checklist {
        margin: 6px 0 0;
        padding: 0;
        list-style: none;
    }

    .guide-checklist li {
        position: relative;
        padding-left: 24px;
        font-size: 13.5px;
        line-height: 1.65;
        color: var(--ink-60);
        margin-bottom: 10px;
    }

    .guide-checklist li:last-child {
        margin-bottom: 0;
    }

    .guide-checklist li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
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
        top: 8px;
        width: 6px;
        height: 3px;
        border-left: 1.6px solid var(--amber);
        border-bottom: 1.6px solid var(--amber);
        transform: rotate(-45deg);
    }

    /* Kode registrasi box (mengikuti warna hijau sukses yang sudah dipakai di halaman lain) */
    .guide-codebox {
        background: #ECFDF5;
        border: 1px solid #A7E8CC;
        border-radius: 12px;
        padding: 14px 18px;
        margin: 4px 0 16px;
    }

    .guide-codebox__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .guide-codebox__label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #065F46;
    }

    .guide-codebox__copy {
        font-size: 11px;
        font-weight: 700;
        color: #065F46;
        background: #fff;
        border: 1px solid #A7E8CC;
        border-radius: 999px;
        padding: 3px 12px;
    }

    .guide-codebox__value {
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        letter-spacing: .02em;
        color: #065F46;
    }

    /* Sub-grid untuk Tab / kartu profil */
    .guide-subgrid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 16px;
    }

    .guide-subcard {
        background: var(--cream);
        border-radius: 12px;
        padding: 18px 18px 20px;
    }

    .guide-subcard__tag {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--amber);
        background: var(--amber-bg);
        border: 1px solid #EDD19B;
        border-radius: 999px;
        padding: 2px 10px;
        margin-bottom: 10px;
    }

    .guide-subcard h4 {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .guide-subcard p {
        font-size: 13px;
        line-height: 1.6;
        color: var(--ink-60);
        margin-bottom: 10px;
    }

    .guide-subcard ol {
        margin: 0;
        padding-left: 18px;
        font-size: 12.5px;
        line-height: 1.65;
        color: var(--ink-60);
    }

    .guide-subcard ol li {
        margin-bottom: 6px;
    }

    .guide-priority {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: var(--ink-60);
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .guide-priority__dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 3px;
    }

    .guide-priority__dot--high { background: var(--amber); }
    .guide-priority__dot--mid { background: var(--amber-lt); }
    .guide-priority__dot--low { background: transparent; border: 1.4px solid var(--ink-30); }

    /* Mini card untuk profil AI */
    .guide-minicard {
        background: var(--white);
        border: 1px solid rgba(171, 168, 159, 0.22);
        border-radius: 12px;
        padding: 18px;
    }

    .guide-minicard h4 {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .guide-minicard p {
        font-size: 12.5px;
        line-height: 1.6;
        color: var(--ink-60);
        margin: 0;
    }

    /* Quote box tips */
    .guide-quote {
        font-family: 'DM Serif Display', serif;
        font-style: italic;
        font-size: 15px;
        line-height: 1.6;
        color: var(--ink);
        background: var(--amber-bg);
        border-left: 3px solid var(--amber);
        border-radius: 0 10px 10px 0;
        padding: 16px 20px;
        margin-bottom: 16px;
    }

    @media (max-width: 900px) {
        .guide-subgrid,
        .guide-subgrid--3 {
            grid-template-columns: 1fr;
        }
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