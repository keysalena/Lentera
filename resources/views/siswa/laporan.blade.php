@extends('layouts.siswa')

@section('title', 'LENTERA - Laporan Digital')

@section('dashboard_content')
<div class="dashboard-laporan" style="animation: fadeIn 0.5s ease-in-out;">
    
    <div style="display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 24px; margin-bottom: 32px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
                Laporan Digital
            </h2>
            <p style="font-size: 15px; color: var(--ink-60); max-width: 600px;">
                Dokumen hasil analisis lengkap untuk keperluan arsip pribadi atau bahan diskusi bersama Guru BK dan Orang Tua.
            </p>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button class="btn-secondary" style="display: flex; align-items: center; gap: 8px; background: var(--white); border: 2px solid var(--cream); padding: 12px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--ink-30)';" onmouseout="this.style.borderColor='var(--cream)';">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M6 9V2H18V9M6 18H4C2.89543 18 2 17.1046 2 16V11C2 9.89543 2.89543 9 4 9H20C21.1046 9 22 9.89543 22 11V16C22 17.1046 21.1046 18 20 18H18M6 14H18V22H6V14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cetak Dokumen
            </button>
            <button class="btn-primary" style="display: flex; align-items: center; gap: 8px; background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-3 3m0 0l-3-3m3 3V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Unduh PDF
            </button>
        </div>
    </div>

    <div style="background: var(--white); padding: 64px 48px; border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.3); box-shadow: 0 24px 64px -12px rgba(87, 94, 112, 0.08); max-width: 850px; margin: 0 auto; position: relative; overflow: hidden;">
        
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 8px; background: var(--amber);"></div>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid var(--ink); padding-bottom: 24px; margin-bottom: 32px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: var(--amber); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                        <path d="M10 2L12.5 7.5L18 8.5L14 12.5L15 18L10 15.5L5 18L6 12.5L2 8.5L7.5 7.5L10 2Z" fill="white" />
                    </svg>
                </div>
                <div>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--ink); letter-spacing: 0.02em; margin-bottom: 2px;">LENTERA</h1>
                    <p style="font-size: 11px; color: var(--ink-60); text-transform: uppercase; letter-spacing: 0.05em;">Sistem Bimbingan Karier AI</p>
                </div>
            </div>
            <div style="text-align: right;">
                <h2 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">LAPORAN ANALISIS MULTIMODAL</h2>
                <p style="font-size: 13px; color: var(--ink-60);">Kode Ref: LNT-2026-X89B2</p>
                <p style="font-size: 13px; color: var(--ink-60);">Tanggal Cetak: 11 Juni 2026</p>
            </div>
        </div>

        <div style="margin-bottom: 40px;">
            <div style="display: inline-block; background: var(--paper); padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 16px; border: 1px solid var(--cream);">
                A. IDENTITAS SISWA
            </div>
            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 12px 24px; font-size: 14px; color: var(--ink);">
                <div style="color: var(--ink-60); font-weight: 600;">Nama Lengkap</div>
                <div style="font-weight: 700;">Keysa Lena Misdona</div>
                
                <div style="color: var(--ink-60); font-weight: 600;">Asal Sekolah</div>
                <div>SMKN 5 Malang</div>
                
                <div style="color: var(--ink-60); font-weight: 600;">Program Keahlian</div>
                <div>Teknik Komputer dan Jaringan</div>
            </div>
        </div>

        <div style="margin-bottom: 40px;">
            <div style="display: inline-block; background: var(--paper); padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 16px; border: 1px solid var(--cream);">
                B. RINGKASAN PEMETAAN KARIER
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div style="border: 1px solid var(--cream); border-radius: 12px; padding: 20px;">
                    <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Kekuatan Akademik (Top 2)</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                        <li style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="font-weight: 600;">Informatika</span>
                            <span style="color: var(--amber); font-weight: 700;">92/100</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="font-weight: 600;">Matematika</span>
                            <span style="color: var(--amber); font-weight: 700;">88/100</span>
                        </li>
                    </ul>
                </div>
                
                <div style="border: 1px solid var(--cream); border-radius: 12px; padding: 20px;">
                    <h4 style="font-size: 12px; font-weight: 700; color: var(--ink-60); text-transform: uppercase; margin-bottom: 8px;">Karakter Dominan (TrOCR)</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                        <li style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="font-weight: 600;">Conscientiousness</span>
                            <span style="color: var(--ink); font-weight: 700;">Tinggi (Tekun)</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="font-weight: 600;">Openness</span>
                            <span style="color: var(--ink); font-weight: 700;">Tinggi (Analitis)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 40px;">
            <div style="display: inline-block; background: var(--paper); padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 16px; border: 1px solid var(--cream);">
                C. REKOMENDASI PERGURUAN TINGGI
            </div>
            <div style="background: var(--amber-bg); border: 1px solid #EDD19B; padding: 24px; border-radius: 12px;">
                <p style="font-size: 14px; color: var(--ink); line-height: 1.6; margin-bottom: 16px;">
                    Berdasarkan fusi data algoritma <em>Machine Learning</em>, Anda dipetakan memiliki kecocokan tertinggi pada rumpun studi <strong>Pendidikan & Teknologi (STEM)</strong>. Pilihan program studi yang paling direkomendasikan adalah:
                </p>
                <div style="display: flex; align-items: center; gap: 16px; background: var(--white); padding: 16px; border-radius: 10px; border: 1px solid var(--cream);">
                    <div style="width: 40px; height: 40px; background: var(--amber); color: var(--white); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-family: 'DM Serif Display', serif;">1</div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 2px;">S1 Pendidikan Teknik Informatika</h3>
                        <p style="font-size: 13px; color: var(--ink-60); margin: 0;">Fakultas Teknik - Tingkat Kecocokan: <span style="font-weight: 700; color: var(--amber);">95%</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 64px;">
            <div style="text-align: center; width: 240px;">
                <p style="font-size: 13px; color: var(--ink); margin-bottom: 8px;">Malang, .............................. 2026</p>
                <p style="font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 64px;">Guru Bimbingan Konseling</p>
                <div style="border-bottom: 1px solid var(--ink); margin-bottom: 8px;"></div>
                <p style="font-size: 11px; color: var(--ink-60);">NIP. .....................................................</p>
            </div>
        </div>
        
    </div>

    <div style="max-width: 850px; margin: 32px auto 0; text-align: center;">
        <div style="background: var(--paper); border: 1px dashed var(--ink-30); border-radius: 16px; padding: 32px; display: inline-block; width: 100%;">
            <h4 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Langkah Selanjutnya</h4>
            <p style="font-size: 14px; color: var(--ink-60); margin-bottom: 20px;">
                Cetak laporan ini dan diskusikan hasilnya secara langsung dengan Guru BK di sekolahmu.
            </p>
            <button class="btn-primary" style="background: var(--ink); color: var(--white); border: none; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='none';">
                Ajukan Jadwal Konseling BK
            </button>
        </div>
    </div>

</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection