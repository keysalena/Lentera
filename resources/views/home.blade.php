@extends('layouts.app')

@section('title', 'LENTERA - Beranda')

@section('content')

<header>
    <div class="hero">
        <div>
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                AI-Powered Guidance
            </div>
            <h1>Pilih Jurusan yang <em>Benar-benar</em> Cocok Untukmu</h1>
            <p>LENTERA menganalisis rekam jejak akademik, skor minat bakat, dan mengekstraksi karakter dari pola tulisan tanganmu — lalu memberikan rekomendasi jurusan kuliah berbasis Machine Learning.</p>
            <div class="hero-actions">
                <a href="{{ route('siswa.dashboard') }}" class="btn-cta">
                    Mulai Analisis
                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="#cara-kerja" class="btn-secondary">Lihat Cara Kerja</a>
            </div>
        </div>

        <div class="hero-right">
            <div class="hero-image-wrapper">
                <img src="{{ asset('img/logo_L.png') }}" alt="Hero Image" class="hero-main-img">
            </div>
        </div>
    </div>
</header>

<section class="section" id="cara-kerja">
    <div class="section-eyebrow">Cara Kerja</div>
    <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:24px; flex-wrap:wrap;">
        <div>
            <h2 class="section-title">Tiga langkah menuju<br>pilihan yang tepat</h2>
        </div>
        <p class="section-sub" style="margin-top:0; padding-bottom:4px;">Prosesnya komprehensif — lengkapi datamu, biarkan AI bekerja, dan dapatkan pemetaan RIASEC yang personal.</p>
    </div>

    <div class="steps">
        <div class="step">
            <div class="step-num">01</div>
            <div class="step-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12H15M9 8H15M9 16H12M5 4H19C19.55 4 20 4.45 20 5V19C20 19.55 19.55 20 19 20H5C4.45 20 4 19.55 4 19V5C4 4.45 4.45 4 5 4Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <h3>Upload Tulisan Tangan</h3>
            <p>Tulis narasi singkat di kertas, foto, lalu unggah. Sistem Computer Vision kami mengekstrak fitur grafologi untuk mengenali sifat analitis dan gaya belajarmu.</p>
        </div>
        <div class="step">
            <div class="step-num">02</div>
            <div class="step-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 17C5 17 5 15 7 15C9 15 9 17 11 17C13 17 13 15 15 15C17 15 17 17 19 17M3 12C5 12 5 10 7 10C9 10 9 12 11 12C13 12 13 10 15 10C17 10 17 12 19 12M3 7C5 7 5 5 7 5C9 5 9 7 11 7C13 7 13 5 15 5C17 5 17 7 19 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                </svg>
            </div>
            <h3>Input Akademik & Bakat</h3>
            <p>Masukkan nilai rapor mata pelajaran inti dan evaluasi mandiri (indikator kemampuan soft-skill) untuk memberikan konteks komputasional pada mesin AI.</p>
        </div>
        <div class="step">
            <div class="step-num">03</div>
            <div class="step-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L15 8.5L22 9.5L17 14.5L18.5 21.5L12 18L5.5 21.5L7 14.5L2 9.5L9 8.5L12 2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <h3>Terima Rekomendasi</h3>
            <p>API Machine Learning kami memproses seluruh variabel secara instan dan menghasilkan Top 3 rekomendasi program studi beserta skor kecocokannya.</p>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="section-eyebrow">Fitur</div>
    <h2 class="section-title">Analisis yang lebih dalam<br>dari sekadar nilai rapor</h2>

    <div class="features-grid">
        <div class="feat-card accent">
            <div class="feat-tag">Computer Vision (TrOCR)</div>
            <h3>Baca Karakter dari Tulisan</h3>
            <p>Sistem mengekstrak fitur kemiringan, tekanan, dan kerapian tulisan tangan berbasis AI untuk mengidentifikasi dimensi psikologis yang tidak tercermin dari angka.</p>
            <div class="feat-deco">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 28C14 28 14 22 18 22C22 22 22 28 28 28M8 20C14 20 14 14 18 14C22 14 22 20 28 20M8 12C14 12 14 8 18 8C22 8 22 12 28 12" stroke="rgba(245,192,122,0.4)" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
        </div>
        <div class="feat-card">
            <div class="feat-tag">Multidimensional Data</div>
            <h3>Integrasi Akademik & Soft-Skill</h3>
            <p>Sistem kami mengenali pola kekuatan siswa dengan menggabungkan nilai eksak (Matematika, Informatika) dengan indikator kompetensi (Logika, Kreativitas, Kepemimpinan).</p>
            <div class="feat-deco">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 26L14 18L20 22L28 10" stroke="rgba(201,123,42,0.35)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
        <div class="feat-card">
            <div class="feat-tag">Machine Learning API</div>
            <h3>Pemetaan Kepribadian RIASEC</h3>
            <p>Hasil akhir dievaluasi menggunakan metode Holland Codes (RIASEC) untuk memastikan kecocokan antara tipe kepribadian (seperti Investigative atau Conventional) dengan bidang karier.</p>
            <div class="feat-deco">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="8" y="6" width="20" height="24" rx="3" stroke="rgba(201,123,42,0.35)" stroke-width="2" />
                    <path d="M12 13H24M12 18H24M12 23H18" stroke="rgba(201,123,42,0.35)" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
        </div>
        <div class="feat-card accent" style="background: var(--amber-bg);">
            <div class="feat-tag" style="background: rgba(201,123,42,.12); color: var(--amber);">Dashboard Guru BK</div>
            <h3 style="color:var(--ink)">Guru BK Punya Akses Penuh</h3>
            <p style="color:var(--ink-60)">Guru Bimbingan Konseling dapat memantau progres pengisian data seluruh siswa dan menjadikan laporan AI LENTERA sebagai referensi komprehensif saat sesi konseling tatap muka.</p>
            <div class="feat-deco">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="18" cy="14" r="5" stroke="rgba(201,123,42,0.4)" stroke-width="2" />
                    <path d="M8 30C8 24.477 12.477 20 18 20C23.523 20 28 24.477 28 30" stroke="rgba(201,123,42,0.4)" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
        </div>
    </div>
</section>
<hr>
<div class="cta-strip">
    <h2>Sudah siap menemukan<br>jurusan yang <em>benar-benar</em> sesuai?</h2>
    <a href="{{ route('siswa.dashboard') }}" class="btn-cta-large">
        Coba LENTERA
        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
</div>

@endsection