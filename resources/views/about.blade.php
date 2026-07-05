@extends('layouts.app')

@section('title', 'LENTERA - Tentang Kami')

@section('content')

<header style="background: var(--paper); border-bottom: 1px solid rgba(171, 168, 159, 0.08);">
    <div class="hero" style="padding: 80px 48px 60px;">
        <div>
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Tentang Platform
            </div>
            <h1 style="font-size: 44px; margin-bottom: 16px;">Menerangi Jalan Menuju <em>Masa Depan</em> Akademik Siswa</h1>
            <p style="max-width: 540px;">
                LENTERA merupakan Platform Pendamping Bimbingan Karier Siswa Berbasis Multimodal Learning Analytics untuk Rekomendasi Jurusan Perguruan Tinggi. Dikembangkan secara khusus sebagai solusi inovatif dalam membantu siswa mengenali minat dan potensi diri secara lebih personal, interaktif, dan reflektif.
            </p>
        </div>
        <div class="hero-right">
            <div class="hero-image-wrapper" style="width: 280px; height: 280px;">
                <!-- <div class="pulse-ring ring-1" style="width: 280px; height: 280px;"></div>
                <div class="pulse-ring ring-2" style="width: 240px; height: 240px;"></div> -->
                <!-- <div class="bg-surface-container-lowest rounded-full flex items-center justify-center" style="width: 200px; height: 200px; background: var(--cream); position: relative; z-index: 10;"> -->
                    <img src="{{ asset('img/logo_L.png') }}" alt="Hero Image" class="hero-main-img">

                <!-- </div> -->
            </div>
        </div>
    </div>
</header>

<div style="background: var(--cream); border-bottom: 1px solid rgba(171, 168, 159, 0.15);">
    <section class="section" style="padding: 80px 48px;">
        <div class="section-eyebrow">Fokus & Visi</div>
        <h2 class="section-title" style="margin-bottom: 32px;">Mengatasi Fenomena Salah Jurusan</h2>

        <div class="features-grid" style="grid-template-columns: 1fr 1fr; gap: 32px;">
            <div style="background: var(--white); padding: 32px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--ink);">Pendampingan Kontekstual</h3>
                <p style="font-size: 14px; line-height: 1.6; color: var(--ink-60);">
                    Banyak siswa menentukan pilihan jurusan tanpa pemahaman diri yang cukup, yang memicu risiko putus studi di perguruan tinggi. LENTERA hadir bukan untuk menggantikan peran penting guru Bimbingan Konseling (BK), melainkan sebagai alat bantu pendukung (Decision Support System) agar proses pendampingan karier dapat dilakukan secara lebih efektif, terarah, dan berbasis data objektif.
                </p>
            </div>
            <div style="background: var(--white); padding: 32px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25);">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--ink);">Pengurangan Bias Asesmen</h3>
                <p style="font-size: 14px; line-height: 1.6; color: var(--ink-60);">
                    Melalui integrasi analisis data ekspresi natural berupa tulisan reflektif dan rekapitulasi nilai akademik, platform ini mampu mengurangi kerentanan social desirability bias yang sering terjadi pada kuesioner pilihan ganda konvensional. Dengan demikian, siswa dapat mengekspresikan diri mereka secara lebih autentik.
                </p>
            </div>
        </div>
    </section>
</div>

<div style="background: var(--white); border-bottom: 1px solid rgba(171, 168, 159, 0.08);">
    <section class="section" style="padding: 80px 48px;">
        <div class="section-eyebrow">Metodologi & Arsitektur</div>
        <h2 class="section-title">Sistem Komputasi Multimodal</h2>
        <p class="section-sub" style="margin-bottom: 48px;">
            LENTERA menggunakan kerangka pengembangan model terstandarisasi untuk mengintegrasikan berbagai aspek data ekspresi dan capaian akademis secara objektif.
        </p>

        <div class="features-grid" style="grid-template-columns: repeat(3, 1fr); gap: 24px;">
            <div class="feat-card" style="background: var(--cream); padding: 32px; border-radius: 16px;">
                <div class="feat-tag" style="background: var(--amber); color: #fff; margin-bottom: 16px;">Computer Vision</div>
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--ink);">Ekstraksi TrOCR</h3>
                <p style="font-size: 13px; line-height: 1.6; color: var(--ink-60);">
                    Mengonversi data visual tulisan tangan reflektif secara otomatis menjadi teks digital untuk mendeteksi karakteristik unik dimensi Big Five Personality secara natural.
                </p>
            </div>

            <div class="feat-card" style="background: var(--cream); padding: 32px; border-radius: 16px;">
                <div class="feat-tag" style="background: var(--ink); color: var(--amber-lt); margin-bottom: 16px;">Machine Learning</div>
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--ink);">Random Forest</h3>
                <p style="font-size: 13px; line-height: 1.6; color: var(--ink-60);">
                    Algoritma klasifikasi tangguh yang bertugas memetakan kecenderungan profil karakter siswa secara cerdas ke dalam lima kelompok besar bidang karier masa depan.
                </p>
            </div>

            <div class="feat-card" style="background: var(--cream); padding: 32px; border-radius: 16px;">
                <div class="feat-tag" style="background: var(--ink); color: var(--amber-lt); margin-bottom: 16px;">Decision Fusion</div>
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--ink);">Meta-Classifier SVM</h3>
                <p style="font-size: 13px; line-height: 1.6; color: var(--ink-60);">
                    Mengintegrasikan hasil prediksi kepribadian dengan rekam jejak performa nilai akademik siswa menggunakan Support Vector Machine (SVM) demi validasi keputusan yang kuat.
                </p>
            </div>
        </div>

        <div style="margin-top: 48px; padding: 24px; background: var(--amber-bg); border-radius: 12px; text-align: left; border: 1px solid #EDD19B;">
            <h4 style="font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Keandalan Berbasis Dataset Masif</h4>
            <p style="font-size: 13px; line-height: 1.6; color: var(--ink-60); margin: 0;">
                Model komputasi dilatih menggunakan ribuan sampel Handwriting Personality Dataset dan dievaluasi ketat menggunakan metode 5-Fold Cross Validation untuk menghasilkan estimasi performa, akurasi, presisi, serta metrik keandalan sistem yang tinggi sebelum diimplementasikan.
            </p>
        </div>
    </section>
</div>

<div style="background: var(--amber-bg);">
    <div class="cta-strip" style="padding: 80px 48px;">
        <h2>Mari mulai petualangan eksplorasi<br>potensi akademikmu sekarang.</h2>
        <a href="{{ route('siswa.dashboard') }}" class="btn-cta-large">
            Mulai Eksplorasi
            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    </div>
</div>

@endsection