<!DOCTYPE html>

<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>LENTERA - Temukan Jurusan Kuliah yang Tepat</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary": "#575e70",
                        "surface-container-lowest": "#ffffff",
                        "tertiary-fixed-dim": "#7fd0ff",
                        "on-error": "#ffffff",
                        "background": "#faf9f5",
                        "surface-dim": "#dbdad6",
                        "secondary-fixed-dim": "#c0c6db",
                        "tertiary-container": "#1abdff",
                        "surface-container-high": "#e9e8e4",
                        "on-tertiary": "#ffffff",
                        "on-primary-container": "#613b00",
                        "on-background": "#1b1c1a",
                        "error": "#ba1a1a",
                        "on-secondary-container": "#5c6274",
                        "surface": "#faf9f5",
                        "on-surface": "#1b1c1a",
                        "on-error-container": "#93000a",
                        "on-tertiary-container": "#004966",
                        "on-surface-variant": "#534434",
                        "inverse-surface": "#2f312e",
                        "secondary-container": "#d9dff5",
                        "primary-fixed-dim": "#ffb95f",
                        "surface-tint": "#855300",
                        "on-primary": "#ffffff",
                        "primary-fixed": "#ffddb8",
                        "surface-container-low": "#f4f4f0",
                        "outline": "#867461",
                        "on-primary-fixed": "#2a1700",
                        "error-container": "#ffdad6",
                        "inverse-primary": "#ffb95f",
                        "tertiary-fixed": "#c5e7ff",
                        "on-secondary": "#ffffff",
                        "surface-container-highest": "#e3e2df",
                        "on-secondary-fixed-variant": "#404758",
                        "on-secondary-fixed": "#141b2b",
                        "surface-container": "#efeeea",
                        "secondary-fixed": "#dce2f7",
                        "on-tertiary-fixed-variant": "#004c6a",
                        "on-tertiary-fixed": "#001e2d",
                        "on-primary-fixed-variant": "#653e00",
                        "outline-variant": "#d8c3ad",
                        "tertiary": "#00658b",
                        "primary": "#855300",
                        "inverse-on-surface": "#f2f1ed",
                        "surface-variant": "#e3e2df",
                        "primary-container": "#f59e0b",
                        "surface-bright": "#faf9f5"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "unit": "8px",
                        "section-gap": "120px",
                        "container-padding-mobile": "24px",
                        "element-gap": "24px",
                        "gutter": "32px",
                        "container-padding-desktop": "64px"
                    },
                    "fontFamily": {
                        "label-md": ["JetBrains Mono"],
                        "display": ["Manrope"],
                        "body-md": ["Manrope"],
                        "headline-md": ["Manrope"],
                        "body-lg": ["Manrope"],
                        "label-sm": ["JetBrains Mono"],
                        "headline-lg-mobile": ["Manrope"],
                        "headline-lg": ["Manrope"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #faf9f5;
            color: #1b1c1a;
            font-family: 'Manrope', sans-serif;
            overflow-x: hidden;
        }

        .glow-amber {
            filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.3));
        }

        .path-flow {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash 5s linear infinite;
        }

        @keyframes dash {
            to {
                stroke-dashoffset: 0;
            }
        }

        .bento-card {
            background: #ffffff;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px -10px rgba(87, 94, 112, 0.05);
        }

        .bento-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 20px 40px -15px rgba(133, 83, 0, 0.1);
        }

        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="antialiased">
    <!-- Navigation Shell -->
    <nav class="fixed top-0 w-full z-50 glass-nav bg-surface/80 shadow-[0_8px_30px_rgb(87,94,112,0.04)] px-container-padding-mobile md:px-container-padding-desktop py-4 flex justify-between items-center">
        <div class="font-display text-headline-md font-bold text-on-surface">LENTERA</div>
        <div class="hidden md:flex items-center gap-12">
            <a class="font-body-md text-body-md text-primary font-bold border-b-2 border-primary pb-1" href="#">Home</a>
            <a class="font-body-md text-body-md text-secondary hover:text-primary transition-colors" href="#">About</a>
        </div>
        <button class="bg-primary-container text-on-primary-container px-8 py-3 rounded-full font-body-md font-bold hover:scale-[1.02] active:scale-[0.98] transition-all shadow-md">
            Mulai Analisis
        </button>
    </nav>
    <!-- Hero Section -->
    <section class="pt-32 pb-24 px-container-padding-mobile md:px-container-padding-desktop flex flex-col lg:flex-row items-center gap-16 min-h-screen relative overflow-hidden">
        <div class="flex-1 z-10">
            <span class="inline-block font-label-md text-label-md px-4 py-1 rounded-full bg-primary/10 text-primary mb-6">AI-Powered Educational Guidance</span>
            <h1 class="font-display text-display lg:text-[64px] mb-8 leading-[1.1]">
                Temukan Jurusan Kuliah yang <span class="text-primary italic">Tepat</span> untuk Masa Depanmu
            </h1>
            <p class="font-body-lg text-body-lg text-secondary mb-12 max-w-2xl">
                LENTERA membantu calon mahasiswa menemukan jurusan kuliah yang sesuai dengan potensi akademik, karakteristik diri, dan prestasi yang dimiliki melalui teknologi Artificial Intelligence, Computer Vision, dan OCR.
            </p>
            <div class="flex flex-col sm:flex-row gap-6">
                <button class="bg-primary text-on-primary px-10 py-5 rounded-full font-body-md font-bold text-lg hover:scale-[1.02] transition-transform shadow-[0_8px_30px_rgba(133,83,0,0.25)]">
                    Mulai Analisis
                </button>
                <button class="border-2 border-outline text-on-surface px-10 py-5 rounded-full font-body-md font-bold text-lg hover:bg-surface-container-low transition-colors">
                    Pelajari Cara Kerja
                </button>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="relative w-full aspect-square max-w-xl mx-auto">
                <img alt="Lentera Hero Illustration" class="w-full h-full object-contain glow-amber" src="https://lh3.googleusercontent.com/aida/AP1WRLunfP4xH44HCg8PvTnuMUik_X6qxg8YOkZv-BrM3AbivhLSd2uozXflSJEgOMwxlw6MY_oT6LW9BUW3SCHbpwobxpzbi4k1ZKAejb7JdCELFWX6aBueiPttZpjnqZTJaPo0FzFixrj0oFwPxcLMRT5sMaoJ0m6X_Bz7KTZKAGzAwm2hLoRqwY31wz3QR_fVyw6rlydYMAh-DA65LJzAdo6-9wiHoY3uYWgTZWS-dD66IpmWS3dDFXlRF9c" />
            </div>
        </div>
    </section>
    <!-- About Summary -->
    <section class="py-section-gap px-container-padding-mobile md:px-container-padding-desktop bg-surface-container-low relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="font-display text-headline-lg mb-8">Apa itu LENTERA?</h2>
            <div class="p-10 md:p-16 rounded-xl bg-white shadow-sm">
                <p class="font-body-lg text-body-lg leading-relaxed text-secondary italic">
                    "LENTERA adalah platform bimbingan karir akademik berbasis kecerdasan buatan yang merevolusi cara siswa memilih jurusan. Kami menggabungkan analisis data nilai akademik tradisional dengan interpretasi psikologis dari tulisan tangan melalui Computer Vision, serta validasi prestasi melalui sistem OCR otomatis untuk memberikan rekomendasi yang benar-benar personal dan akurat."
                </p>
            </div>
        </div>
        <!-- Decorative subtle gradient -->
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-primary-fixed opacity-10 rounded-full blur-3xl"></div>
    </section>
    <!-- How It Works - Fluid Pathway -->
    <section class="py-section-gap px-container-padding-mobile md:px-container-padding-desktop">
        <div class="text-center mb-24">
            <h2 class="font-display text-headline-lg mb-4">Proses Kerja Cerdas</h2>
            <p class="text-secondary font-body-md">Langkah sederhana menuju masa depan yang lebih terarah</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 relative">
            <!-- Fluid Connector Background (Visible on Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-primary/10 -translate-y-1/2 -z-10">
                <div class="h-full bg-primary/40 w-1/2 rounded-full path-flow"></div>
            </div>
            <!-- Steps -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-16 h-16 rounded-full bg-white shadow-lg border border-primary/20 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-3xl">edit_note</span>
                </div>
                <h4 class="font-headline-md text-lg mb-2">1. Input Nilai</h4>
                <p class="text-secondary text-sm px-4">Masukkan data nilai akademik per semester secara manual atau auto-import.</p>
            </div>
            <div class="flex flex-col items-center text-center group">
                <div class="w-16 h-16 rounded-full bg-white shadow-lg border border-primary/20 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-3xl">18_up_rating</span>
                </div>
                <h4 class="font-headline-md text-lg mb-2">2. Upload Tulisan</h4>
                <p class="text-secondary text-sm px-4">Unggah foto tulisan tangan Anda untuk analisis karakteristik melalui Computer Vision.</p>
            </div>
            <div class="flex flex-col items-center text-center group">
                <div class="w-16 h-16 rounded-full bg-white shadow-lg border border-primary/20 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-3xl">workspace_premium</span>
                </div>
                <h4 class="font-headline-md text-lg mb-2">3. Upload Sertifikat</h4>
                <p class="text-secondary text-sm px-4">Sistem OCR kami akan mengekstrak data prestasi dari sertifikat perlombaan Anda.</p>
            </div>
            <div class="flex flex-col items-center text-center group">
                <div class="w-16 h-16 rounded-full bg-white shadow-lg border border-primary/20 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-3xl">psychology</span>
                </div>
                <h4 class="font-headline-md text-lg mb-2">4. Analisis AI</h4>
                <p class="text-secondary text-sm px-4">Algoritma LENTERA memproses seluruh data untuk memetakan kecocokan karir.</p>
            </div>
            <div class="flex flex-col items-center text-center group">
                <div class="w-16 h-16 rounded-full bg-primary shadow-xl flex items-center justify-center mb-6 text-white scale-110">
                    <span class="material-symbols-outlined text-3xl">emoji_objects</span>
                </div>
                <h4 class="font-headline-md text-lg mb-2">5. Rekomendasi</h4>
                <p class="text-secondary text-sm px-4">Dapatkan daftar jurusan kuliah terbaik dengan skor akurasi tinggi.</p>
            </div>
        </div>
    </section>
    <!-- Advantages - Bento Grid Style -->
    <section class="py-section-gap px-container-padding-mobile md:px-container-padding-desktop bg-surface-container">
        <h2 class="font-display text-headline-lg text-center mb-16">Keunggulan Teknologi Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 h-auto">
            <div class="md:col-span-8 bento-card p-10 rounded-xl flex flex-col justify-between overflow-hidden relative group">
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4">Analisis Akademik Mendalam</h3>
                    <p class="text-secondary max-w-md">Pemetaan tren nilai dari kelas 10-12 untuk melihat pola kecenderungan minat subjek dan performa kognitif jangka panjang.</p>
                </div>
                <div class="absolute -right-10 -bottom-10 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-[200px]">trending_up</span>
                </div>
            </div>
            <div class="md:col-span-4 bento-card p-10 rounded-xl bg-primary text-on-primary">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-white text-3xl">visibility</span>
                </div>
                <h3 class="font-headline-lg text-2xl mb-4">Computer Vision</h3>
                <p class="text-white/80">Analisis grafologi digital untuk mengidentifikasi profil psikologis dan soft skills melalui pola tulisan tangan.</p>
            </div>
            <div class="md:col-span-4 bento-card p-10 rounded-xl">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">document_scanner</span>
                </div>
                <h3 class="font-headline-lg text-2xl mb-4">OCR Sertifikat</h3>
                <p class="text-secondary">Verifikasi otomatis poin prestasi dari ratusan format sertifikat berbeda secara instan dan akurat.</p>
            </div>
            <div class="md:col-span-8 bento-card p-10 rounded-xl flex items-center justify-between gap-8 bg-white border border-primary/5">
                <div class="flex-1">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-primary text-3xl">auto_awesome</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4">Rekomendasi Berbasis AI</h3>
                    <p class="text-secondary">Model AI yang terus berkembang berdasarkan ribuan data lulusan sukses di berbagai bidang industri.</p>
                </div>
                <div class="hidden lg:block w-48 h-48 rounded-full border-4 border-dashed border-primary/20 p-4 animate-[spin_20s_linear_infinite]">
                    <div class="w-full h-full rounded-full bg-primary/5 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-4xl">hub</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sample Results -->
    <section class="py-section-gap px-container-padding-mobile md:px-container-padding-desktop">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-[0_32px_64px_-16px_rgba(87,94,112,0.1)] overflow-hidden">
                <div class="bg-primary p-8 text-on-primary flex justify-between items-center">
                    <div>
                        <h3 class="font-headline-lg text-2xl">Simulasi Hasil Analisis</h3>
                        <p class="opacity-80">Profil: Siswa Jurusan IPA</p>
                    </div>
                    <span class="material-symbols-outlined text-4xl">equalizer</span>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <span class="font-bold text-lg">Teknik Informatika</span>
                                <span class="text-primary font-bold">94% Match</span>
                            </div>
                            <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary w-[94%] rounded-full shadow-[0_0_10px_rgba(133,83,0,0.3)]"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <span class="font-bold text-lg">Sistem Informasi</span>
                                <span class="text-primary font-bold">91% Match</span>
                            </div>
                            <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary/80 w-[91%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <span class="font-bold text-lg">Ilmu Komputer</span>
                                <span class="text-primary font-bold">89% Match</span>
                            </div>
                            <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary/60 w-[89%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <span class="font-bold text-lg">Teknik Elektro</span>
                                <span class="text-primary font-bold">83% Match</span>
                            </div>
                            <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary/40 w-[83%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonials -->
    <section class="py-section-gap px-container-padding-mobile md:px-container-padding-desktop bg-surface-container-low">
        <h2 class="font-display text-headline-lg text-center mb-16">Kisah Sukses Pengguna</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-surface-container">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary-fixed overflow-hidden">
                        <img alt="Andi" class="w-full h-full object-cover" data-alt="A portrait illustration of a confident young Indonesian man named Andi Pratama, smiling warmly. He is dressed in a smart-casual white shirt, set against a soft blurred background of a modern university library. The lighting is bright and natural, reinforcing a sense of clarity and future success." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAxjTPoAaZu_cJwRLSjJK8FpucWLhqiNayRJ9eBGGvqHtgeA9w7v0ccFTDNrk11qEhSilYDM-bb2G5npQApWHv5-vVFSKmbvPEFuxDlMkNtTbOsx7FgnKB8hLqsIOubQ8nagreA7bcAUMMDERS6U6tmDLyhqVEmO-kXrVJ4iqIPCt3Q3HJ4yHhbFIpENRby8gYP--5cJLVO7bLV_Kn2HtK34b1kc125XVX2N5EMvIwV4W59A9WMlkEZk2UgHJ7OFxEqzoN7RHaCCL8" />
                    </div>
                    <div>
                        <h5 class="font-bold">Andi Pratama</h5>
                        <p class="text-sm text-secondary">Mhs Teknik Informatika</p>
                    </div>
                </div>
                <p class="text-secondary leading-relaxed">"Awalnya ragu antara IT atau Ekonomi. Analisis LENTERA benar-benar membuka mata bahwa potensi saya memang di logika coding. Sekarang IPK saya 3.9!"</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-sm border border-surface-container scale-105 ring-2 ring-primary/10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary-fixed overflow-hidden">
                        <img alt="Siti" class="w-full h-full object-cover" data-alt="A cheerful young Indonesian woman named Siti Rahma, wearing a stylish pastel hijab and a professional blazer. She is holding a tablet, standing in a brightly lit, modern digital workspace. The overall atmosphere is encouraging and professional, aligned with high-end modern UI aesthetics." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpC8lJ8mcQo0mCOHPwFv4aHrc-wdt2b5yNIaYJ93r6yVRvEJ2f0jtRYzZbvBpDsopBkBVGsSmbcIOaiSauAxwveD6OYwDdHRHzmG12yczelIlWnh7uJKd9YDvdBB9kUNUC6TNIcYNY09WuKWtF01UYSa4ojXFZcsxOuGs58KROWxljq0iVZZJ2ZI9LfPWkPfLogR4sN_425RIsxTg-04dkehIZzrnayn0SaGdnOYYzQsUogf6f30OmQjdOrQ32KT_KmbCgSf7cnWs" />
                    </div>
                    <div>
                        <h5 class="font-bold">Siti Rahma</h5>
                        <p class="text-sm text-secondary">Mhs Psikologi</p>
                    </div>
                </div>
                <p class="text-secondary leading-relaxed">"Sertifikat olimpiade saya yang banyak jadi tidak sia-sia. LENTERA membantu memvalidasi bahwa passion saya di psikologi sangat kuat. Akurasinya luar biasa."</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-sm border border-surface-container">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary-fixed overflow-hidden">
                        <img alt="Dimas" class="w-full h-full object-cover" data-alt="A focused young Indonesian man named Dimas Nugraha, wearing a navy blue collegiate jacket. He is standing in a lush, green outdoor campus setting during golden hour. The soft warm lighting reflects the enlightened and modern personality of the Lentera brand." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-XkR14UzuL4BOhDW2XrfYQIwPgmyRKP6vXNBkq1RqLmRweoTZt7h3Q4FgUWuErDDYwIg2dlLKJESs0M2ivdgVNJ2o5rB4OIX9W4Pcu1icWgGcbnUeGCUNqsCn6oX4F2kpMLt5OUkIkD1BZElBwJUuG9-emzGULfpRb727fIr8yks8CumbdfhYD6STF_jGwK7MEfH5964yic0cl0Bd4yA8WT8kb3faGBv1ZTiIYc-0bT2mPZz96gAeR9-yxSGPHTrmd0FNG5sd6PE" />
                    </div>
                    <div>
                        <h5 class="font-bold">Dimas Nugraha</h5>
                        <p class="text-sm text-secondary">Mhs Desain Komunikasi Visual</p>
                    </div>
                </div>
                <p class="text-secondary leading-relaxed">"Analisis tulisan tangan LENTERA unik banget! Ternyata beneran bisa baca karakter kreatif saya. Gak nyesel pilih DKV sesuai rekomendasi AI-nya."</p>
            </div>
        </div>
    </section>
    <!-- FAQ -->
    <section class="py-section-gap px-container-padding-mobile md:px-container-padding-desktop max-w-4xl mx-auto">
        <h2 class="font-display text-headline-lg text-center mb-12">Pertanyaan Umum</h2>
        <div class="space-y-4">
            <details class="group bg-white p-6 rounded-xl border border-surface-container transition-all open:ring-2 open:ring-primary/20">
                <summary class="list-none cursor-pointer flex justify-between items-center font-bold text-lg">
                    Apakah rekomendasi ini menjamin masa depan saya?
                    <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="mt-4 text-secondary leading-relaxed">Rekomendasi LENTERA didasarkan pada data objektif dan analisis psikologis mendalam untuk memberikan arahan yang paling sesuai dengan potensi Anda. Keputusan akhir tetap di tangan Anda, namun kami menyediakan data komprehensif untuk mendukung keputusan yang lebih cerdas.</p>
            </details>
            <details class="group bg-white p-6 rounded-xl border border-surface-container transition-all open:ring-2 open:ring-primary/20">
                <summary class="list-none cursor-pointer flex justify-between items-center font-bold text-lg">
                    Bagaimana jika saya belum memiliki banyak sertifikat?
                    <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="mt-4 text-secondary leading-relaxed">Tidak masalah! Sistem kami tetap dapat bekerja optimal dengan data nilai akademik dan analisis tulisan tangan. Sertifikat hanya berfungsi sebagai faktor penguat tambahan dalam model AI kami.</p>
            </details>
            <details class="group bg-white p-6 rounded-xl border border-surface-container transition-all open:ring-2 open:ring-primary/20">
                <summary class="list-none cursor-pointer flex justify-between items-center font-bold text-lg">
                    Seberapa aman data pribadi saya di LENTERA?
                    <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="mt-4 text-secondary leading-relaxed">Kami menggunakan enkripsi tingkat bank untuk melindungi seluruh data akademik dan dokumen yang Anda unggah. Data hanya digunakan untuk keperluan analisis mesin dan tidak akan dibagikan kepada pihak ketiga manapun.</p>
            </details>
            <details class="group bg-white p-6 rounded-xl border border-surface-container transition-all open:ring-2 open:ring-primary/20">
                <summary class="list-none cursor-pointer flex justify-between items-center font-bold text-lg">
                    Berapa tingkat akurasi hasil analisisnya?
                    <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="mt-4 text-secondary leading-relaxed">Berdasarkan data user testing kami, tingkat kepuasan dan kesesuaian rekomendasi mencapai 92%. Kami terus memperbarui algoritma kami seiring dengan bertambahnya basis data dari alumni universitas terkemuka.</p>
            </details>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-surface-container-low px-container-padding-mobile md:px-container-padding-desktop py-12 flex flex-col md:flex-row justify-between items-center gap-8 border-t border-surface-container">
        <div class="font-display text-headline-md font-bold text-on-surface">LENTERA</div>
        <div class="flex gap-8">
            <a class="text-secondary hover:text-primary transition-colors font-body-md" href="#">Privacy Policy</a>
            <a class="text-secondary hover:text-primary transition-colors font-body-md" href="#">Terms of Service</a>
            <a class="text-secondary hover:text-primary transition-colors font-body-md" href="#">Contact Us</a>
        </div>
        <div class="text-secondary font-body-md">
            © 2024 LENTERA AI. All rights reserved.
        </div>
    </footer>
    <script>
        // Smooth reveal animations on scroll
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }, observerOptions);

        document.querySelectorAll('section > div').forEach(el => {
            el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
            observer.observe(el);
        });

        // Hover scale effect for primary buttons
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('mousedown', () => {
                btn.style.transform = 'scale(0.95)';
            });
            btn.addEventListener('mouseup', () => {
                btn.style.transform = '';
            });
        });
    </script>
</body>

</html>