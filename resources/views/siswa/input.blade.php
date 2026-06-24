@extends('layouts.siswa')
@section('title', 'LENTERA - Input Eksplorasi')

@section('dashboard_content')
<div class="dashboard-input-wizard" style="animation: fadeIn 0.4s ease-in-out;">

    @if(session('error'))
    <div style="background: #FEF2F2; color: #991B1B; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600;">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div style="background: #F0FDF4; color: #166534; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600;">
        {{ session('success') }}
    </div>
    @endif

    <div style="margin-bottom: 32px;">
        <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 8px;">
            Input Eksplorasi Data (tulis dengan sesuaikan berdasarkan kegiatan paling bermakna untukmu)
        </h2>
        <p style="font-size: 15px; color: var(--ink-60); max-width: 600px;">
            Lengkapi tahapan pengisian data di bawah ini. Anda dapat mengedit data yang sudah tersimpan sebelum melakukan finalisasi analisis.
        </p>
    </div>

    <div style="display: flex; align-items: center; margin-bottom: 32px; max-width: 700px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; 
                background: {{ $step >= 1 ? 'var(--amber)' : 'var(--white)' }}; 
                color: {{ $step >= 1 ? 'var(--white)' : 'var(--ink-30)' }};
                border: {{ $step >= 1 ? 'none' : '2px solid var(--cream)' }};">1</div>
            <span style="font-size: 14px; font-weight: {{ $step >= 1 ? '700' : '500' }}; color: {{ $step >= 1 ? 'var(--ink)' : 'var(--ink-30)' }};">Tulisan Reflektif</span>
        </div>
        <div style="height: 2px; background: var(--cream); flex: 1; margin: 0 16px; position: relative;">
            <div style="position: absolute; top: 0; left: 0; height: 100%; background: var(--amber); width: {{ $step > 1 ? '100%' : '0%' }};"></div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700;
                background: {{ $step >= 2 ? 'var(--amber)' : 'var(--white)' }}; 
                color: {{ $step >= 2 ? 'var(--white)' : 'var(--ink-30)' }};
                border: {{ $step >= 2 ? 'none' : '2px solid var(--cream)' }};">2</div>
            <span style="font-size: 14px; font-weight: {{ $step >= 2 ? '700' : '500' }}; color: {{ $step >= 2 ? 'var(--ink)' : 'var(--ink-30)' }};">Data Akademik</span>
        </div>
        <div style="height: 2px; background: var(--cream); flex: 1; margin: 0 16px; position: relative;">
            <div style="position: absolute; top: 0; left: 0; height: 100%; background: var(--amber); width: {{ $step > 2 ? '100%' : '0%' }};"></div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700;
                background: {{ $step >= 3 ? 'var(--amber)' : 'var(--white)' }}; 
                color: {{ $step >= 3 ? 'var(--white)' : 'var(--ink-30)' }};
                border: {{ $step >= 3 ? 'none' : '2px solid var(--cream)' }};">3</div>
            <span style="font-size: 14px; font-weight: {{ $step >= 3 ? '700' : '500' }}; color: {{ $step >= 3 ? 'var(--ink)' : 'var(--ink-30)' }};">Kemampuan</span>
        </div>
    </div>

    <div>
        <div style="background: var(--white); padding: 40px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02); max-width: 900px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 1px solid var(--cream); padding-bottom: 16px;">
                <div>
                    <div style="display: inline-block; background: #FFF5F5; color: #BA1A1A; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; margin-bottom: 8px;">TAHAP 1 (WAJIB)</div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Dokumen Tulisan Reflektif</h3>
                </div>
                @if($gambar && $edit_step != 1)
                    <a href="?edit_step=1" style="background: var(--paper); color: var(--ink); border: 1px solid var(--ink-30); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none;">Edit Dokumen</a>
                @endif
            </div>

            @if($gambar && $edit_step != 1)
                <div style="display: flex; align-items: center; gap: 16px; background: #F0FDF4; padding: 20px; border-radius: 12px; border: 1px solid #A7F3D0;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px; color: #10B981;">
                        <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: #065F46; margin-bottom: 2px;">Dokumen Telah Tersimpan</h4>
                        <a href="{{ asset('storage/' . $gambar->gambar) }}" target="_blank" style="font-size: 13px; color: #047857; text-decoration: underline;">Lihat Gambar yang Diunggah</a>
                    </div>
                </div>
            @else
                <form action="{{ route('siswa.eksplorasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="step" value="1">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: stretch;">
                        <div style="display: flex; flex-direction: column;">
                            <label style="font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 12px;">Area Unggah Foto Tulisan</label>
                            <div class="dropzone-area" id="dropzone-container" style="border: 2px dashed var(--ink-30); background: var(--paper); border-radius: 14px; padding: 48px 24px; text-align: center; cursor: pointer; transition: all 0.2s; flex: 1; display: flex; flex-direction: column; justify-content: center;" onclick="document.getElementById('tulisan_tangan').click()">
                                <input type="file" id="tulisan_tangan" name="tulisan_tangan" required accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewFileName(this)">
                                
                                <div id="dropzone-text-1">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: var(--amber); margin: 0 auto 16px;">
                                        <path d="M12 16V8M12 8L9 11M12 8L15 11M3 15V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span style="font-size: 14px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px;">Pilih foto tulisan Anda</span>
                                    <span style="font-size: 12px; color: var(--ink-30);">Format JPG/PNG (Maks 5MB)</span>
                                </div>
                                <div id="file-preview-1" style="display: none; align-items: center; justify-content: center; gap: 10px; flex-direction: column;">
                                    <span id="file-name-display-1" style="font-size: 14px; font-weight: 700; color: #10B981;">File Terpilih</span>
                                </div>
                            </div>
                        </div>

                        <div style="background: var(--amber-bg); border: 1px solid #EDD19B; border-radius: 14px; padding: 24px; position: relative;">
                            <div style="position: absolute; top: -12px; right: 24px; background: var(--amber); color: var(--white); padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700;">CONTOH</div>
                            <h4 style="font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 12px;">Referensi Topik Narasi</h4>
                            <div style="background: rgba(255, 255, 255, 0.6); padding: 16px; border-radius: 10px;">
                                <p style="font-family: 'DM Serif Display', serif; font-style: italic; font-size: 14px; color: var(--ink-60); line-height: 1.6; margin: 0;">
                                    "Bagi saya, kegiatan yang paling bermakna adalah ketika saya berhasil memecahkan masalah logika atau merakit sesuatu..."
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--cream);">
                        @if($gambar && $edit_step == 1)
                            <a href="{{ route('siswa.input') }}" style="background: transparent; color: var(--ink-60); padding: 12px 24px; border: 1px solid var(--ink-30); border-radius: 8px; font-weight: 700; text-decoration: none; display: flex; align-items: center;">Batal</a>
                        @endif
                        <button type="submit" class="btn-primary" style="background: var(--amber); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan Dokumen</button>
                    </div>
                </form>
            @endif
        </div>

        @if($step >= 2)
        <div style="background: var(--white); padding: 40px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02); max-width: 900px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 1px solid var(--cream); padding-bottom: 16px;">
                <div>
                    <div style="display: inline-block; background: rgba(201, 123, 42, 0.12); color: var(--amber); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; margin-bottom: 8px;">TAHAP 2 (OPSIONAL)</div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Data Nilai Akademik (Rapor)</h3>
                </div>
                @if(!empty($nilai) && $edit_step != 2)
                    <a href="?edit_step=2" style="background: var(--paper); color: var(--ink); border: 1px solid var(--ink-30); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none;">Edit Nilai</a>
                @endif
            </div>

            @if(!empty($nilai) && $edit_step != 2)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
                    @foreach($mapels as $mapel)
                        @if(isset($nilai[$mapel->id_mapel]) && $nilai[$mapel->id_mapel] !== null)
                            <div style="background: var(--paper); padding: 16px; border-radius: 10px; border: 1px solid var(--cream);">
                                <div style="font-size: 11px; color: var(--ink-60); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">{{ $mapel->nama_mapel }}</div>
                                <div style="font-size: 18px; font-weight: 800; color: var(--ink);">{{ $nilai[$mapel->id_mapel] }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <form action="{{ route('siswa.eksplorasi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="step" value="2">
                    <p style="font-size: 13px; color: var(--ink-60); margin-bottom: 20px;">Masukkan rata-rata nilai rapor (skala 0 - 100). Kosongkan untuk mata pelajaran yang tidak ada atau tidak relevan.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        @forelse($mapels as $mapel)
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">{{ $mapel->nama_mapel }}</label>
                            <input type="number" name="mapel_{{ $mapel->id_mapel }}" value="{{ $nilai[$mapel->id_mapel] ?? '' }}" min="0" max="100" placeholder="0 - 100" class="input-form">
                        </div>
                        @empty
                        <div style="grid-column: 1 / -1; font-size: 13px; color: #BA1A1A;">Data mata pelajaran belum dikonfigurasi.</div>
                        @endforelse
                    </div>
                    
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--cream);">
                        @if(!empty($nilai) && $edit_step == 2)
                            <a href="{{ route('siswa.input') }}" style="background: transparent; color: var(--ink-60); padding: 12px 24px; border: 1px solid var(--ink-30); border-radius: 8px; font-weight: 700; text-decoration: none; display: flex; align-items: center;">Batal</a>
                        @endif
                        <button type="submit" style="background: var(--amber); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan Nilai Akademik</button>
                    </div>
                </form>
            @endif
        </div>
        @endif

        @if($step >= 3)
        <div style="background: var(--white); padding: 40px; border-radius: 20px; border: 1px solid rgba(171, 168, 159, 0.25); box-shadow: 0 4px 24px rgba(87, 94, 112, 0.02); max-width: 900px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 1px solid var(--cream); padding-bottom: 16px;">
                <div>
                    <div style="display: inline-block; background: rgba(201, 123, 42, 0.12); color: var(--amber); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; margin-bottom: 8px;">TAHAP 3 (OPSIONAL)</div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--ink);">Penilaian Minat & Kemampuan</h3>
                </div>
                @if(!empty($skor) && $edit_step != 3)
                    <a href="?edit_step=3" style="background: var(--paper); color: var(--ink); border: 1px solid var(--ink-30); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none;">Edit Skor</a>
                @endif
            </div>

            @if(!empty($skor) && $edit_step != 3)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
                    @foreach($kemampuans as $kemampuan)
                        @if(isset($skor[$kemampuan->id_kemampuan]) && $skor[$kemampuan->id_kemampuan] !== null)
                            <div style="background: var(--paper); padding: 16px; border-radius: 10px; border: 1px solid var(--cream);">
                                <div style="font-size: 11px; color: var(--ink-60); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">{{ $kemampuan->nama_kemampuan }}</div>
                                <div style="font-size: 18px; font-weight: 800; color: var(--amber);">{{ $skor[$kemampuan->id_kemampuan] }} <span style="font-size: 12px; color: var(--ink-30); font-weight: 600;">/ 10</span></div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                <form action="{{ route('siswa.eksplorasi.store') }}" method="POST" style="margin-top: 32px; text-align: right; border-top: 1px solid var(--cream); padding-top: 24px;">
                    @csrf
                    <input type="hidden" name="step" value="3">
                    
                    <input type="hidden" name="action_finalisasi" value="1">
                    
                    <button type="submit" onclick="this.innerText='Memproses AI...'; this.style.opacity='0.7';" style="background: var(--ink); color: var(--white); border: none; padding: 12px 28px; font-size: 14px; font-weight: 700; border-radius: 10px; cursor: pointer;">
                        Finalisasi & Analisis Hasil Laporan
                    </button>
                </form>
            @else
                <form action="{{ route('siswa.eksplorasi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="step" value="3">
                    <p style="font-size: 13px; color: var(--ink-60); margin-bottom: 20px;">Seberapa baik kemampuan Anda? Pilih angka <strong>1 hingga 10</strong>. Kosongkan jika tidak relevan.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                        @forelse($kemampuans as $kemampuan)
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px;">{{ $kemampuan->nama_kemampuan }}</label>
                            <select name="kemampuan_{{ $kemampuan->id_kemampuan }}" class="input-form" style="cursor: pointer;">
                                <option value="">Pilih Skor...</option>
                                @for($i = 1; $i <= 10; $i++) 
                                    <option value="{{ $i }}" {{ (isset($skor[$kemampuan->id_kemampuan]) && $skor[$kemampuan->id_kemampuan] == $i) ? 'selected' : '' }}>{{ $i }}</option> 
                                @endfor
                            </select>
                        </div>
                        @empty
                        <div style="grid-column: 1 / -1; font-size: 13px; color: #BA1A1A;">Data indikator kemampuan belum dikonfigurasi.</div>
                        @endforelse
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--cream);">
                        @if(!empty($skor) && $edit_step == 3)
                            <a href="{{ route('siswa.input') }}" style="background: transparent; color: var(--ink-60); padding: 12px 28px; border: 1px solid var(--ink-30); border-radius: 8px; font-weight: 700; text-decoration: none; display: flex; align-items: center;">Batal</a>
                        @endif
                        <button type="submit" style="background: var(--ink); color: white; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan & Analisis Hasil</button>
                    </div>
                </form>
            @endif
        </div>
        @endif

    </div>
</div>

<script>
    function previewFileName(input) {
        const dropzoneText = document.getElementById('dropzone-text-1');
        const filePreview = document.getElementById('file-preview-1');
        const fileNameDisplay = document.getElementById('file-name-display-1');
        const parentElement = document.getElementById('dropzone-container');

        if (input.files && input.files[0]) {
            dropzoneText.style.display = 'none';
            filePreview.style.display = 'flex';
            fileNameDisplay.innerText = input.files[0].name;
            parentElement.style.borderColor = '#10B981';
            parentElement.style.backgroundColor = '#F0FDF4';
        }
    }
</script>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .dropzone-area:hover { border-color: var(--amber) !important; background-color: var(--amber-bg) !important; }
    .input-form { width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; background: var(--paper); outline: none; transition: border-color 0.2s; }
    .input-form:focus { border-color: var(--amber) !important; box-shadow: 0 0 0 3px rgba(201, 123, 42, 0.15); }
</style>
@endsection