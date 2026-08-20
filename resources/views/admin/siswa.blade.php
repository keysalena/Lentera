@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-siswa" style="animation: fadeIn 0.4s ease-in-out;">
    
    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border: 1px solid #34D399;">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color: #065F46; cursor:pointer; font-size: 20px; font-weight: bold;">&times;</button>
    </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Data Seluruh Siswa</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Pantau dan kelola akun siswa dari seluruh sekolah mitra.</p>
        </div>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.siswa') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin: 0;">
                
                <select name="filter_sekolah" onchange="this.form.submit()"
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 13px; background: var(--white); color: var(--ink); outline: none; cursor: pointer; min-width: 160px;">
                    <option value="all">Semua Sekolah</option>
                    @foreach($sekolahs as $sekolah)
                        <option value="{{ $sekolah->id_sekolah }}" {{ request('filter_sekolah') == $sekolah->id_sekolah ? 'selected' : '' }}>
                            {{ $sekolah->nama_sekolah }}
                        </option>
                    @endforeach
                </select>

                <select name="filter_angkatan" onchange="this.form.submit()"
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 13px; background: var(--white); color: var(--ink); outline: none; cursor: pointer; min-width: 140px;">
                    <option value="all">Semua Angkatan</option>
                    @foreach($angkatans as $angkatan)
                        <option value="{{ $angkatan }}" {{ request('filter_angkatan') == $angkatan ? 'selected' : '' }}>
                            Angkatan {{ $angkatan }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email..." 
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 240px; outline: none; font-size: 13px;">
                
                <button type="submit" style="background: var(--ink); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">
                    Cari
                </button>
            </form>

            <button onclick="openAddModal()" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA SISWA</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">ASAL SEKOLAH</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">ANGKATAN</th>
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
                        {{ optional($siswa->sekolah)->nama_sekolah ?? 'Tidak Terikat' }}
                    </td>
                    <td style="padding: 20px 24px; font-size: 14px; color: var(--ink-60);">
                        {{ optional($siswa->siswa)->angkatan ?? '-' }}
                    </td>
                    <td style="padding: 20px 24px;">
                        @if(Str::startsWith(optional($siswa->siswa)->nisn, 'S'))
                            <span style="background: #FFF7ED; color: #EA580C; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">PROGRES</span>
                        @else
                            <span style="background: #E0F2FE; color: #0284C7; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">LENGKAP</span>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <button onclick="openEditModal('{{ $siswa->id }}', '{{ addslashes($siswa->nama) }}', '{{ $siswa->email }}', '{{ $siswa->id_sekolah }}', '{{ optional($siswa->siswa)->nisn }}', '{{ optional($siswa->siswa)->jenis_kelamin }}', '{{ optional($siswa->siswa)->angkatan }}')" style="background:none; border:none; color: var(--ink-60); font-weight: 600; font-size: 13px; cursor: pointer; padding:0;">
                                Edit
                            </button>
                            <span style="color: var(--cream);">|</span>
                            
                            <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $siswa->nama }}?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: #BA1A1A; font-weight: 600; font-size: 13px; cursor: pointer; padding:0;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                        Tidak ada data siswa yang ditemukan.
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

    <div id="siswaModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: var(--white); width: 100%; max-width: 520px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; animation: fadeIn 0.3s ease-out;">

            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="modalTitle" style="font-size: 18px; font-weight: 700; color: var(--ink);">Tambah Akun Siswa Baru</h3>
                <button onclick="closeModal('siswaModal')" style="background: none; border: none; font-size: 24px; color: var(--ink-30); cursor: pointer;">&times;</button>
            </div>

            <form id="siswaForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div style="padding: 32px; max-height: 65vh; overflow-y: auto; display: flex; flex-direction: column; gap: 18px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Nama Lengkap</label>
                        <input type="text" id="inputNama" name="nama" required placeholder="Contoh: Keysa Lena Misdona" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">NISN</label>
                            <input type="text" id="inputNisn" name="nisn" required placeholder="Contres: 00612345..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Angkatan</label>
                            <input type="text" id="inputAngkatan" name="angkatan" required placeholder="Contoh: 2024" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Jenis Kelamin</label>
                        <select id="inputJk" name="jenis_kelamin" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none; cursor: pointer;">
                            <option value="" disabled selected>Pilih Jenis Kelamin...</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Alamat Email</label>
                        <input type="email" id="inputEmail" name="email" required placeholder="nama@student.sch.id" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Asal Sekolah Mitra</label>
                        <select id="inputSekolah" name="id_sekolah" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none; cursor: pointer;">
                            <option value="" disabled selected>Pilih Instansi Sekolah...</option>
                            @foreach($sekolahs as $sekolah)
                                <option value="{{ $sekolah->id_sekolah }}">{{ $sekolah->nama_sekolah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Kata Sandi</label>
                        <input type="password" id="inputPassword" name="password" placeholder="Minimal 8 karakter" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                        <p id="passwordNote" style="font-size: 11px; color: var(--ink-60); margin-top: 4px; display: none;">*Kosongkan jika tidak ingin mengubah sandi lama.</p>
                    </div>
                </div>

                <div style="padding: 24px 32px; background: #FAFAF9; border-top: 1px solid var(--cream); display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeModal('siswaModal')" style="padding: 12px 24px; background: transparent; border: 1px solid var(--ink-30); color: var(--ink-60); border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 12px 24px; background: var(--amber); border: none; color: white; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const modal = document.getElementById('siswaModal');
    const form = document.getElementById('siswaForm');
    const title = document.getElementById('modalTitle');
    const methodInput = document.getElementById('formMethod');
    
    const inputNama = document.getElementById('inputNama');
    const inputNisn = document.getElementById('inputNisn');
    const inputAngkatan = document.getElementById('inputAngkatan');
    const inputJk = document.getElementById('inputJk');
    const inputEmail = document.getElementById('inputEmail');
    const inputSekolah = document.getElementById('inputSekolah');
    const inputPassword = document.getElementById('inputPassword');
    const passwordNote = document.getElementById('passwordNote');

    function openAddModal() {
        title.innerText = 'Tambah Akun Siswa Baru';
        form.action = "{{ route('admin.siswa.store') }}";
        methodInput.value = 'POST';
        
        inputPassword.setAttribute('required', 'required');
        passwordNote.style.display = 'none';

        
        inputNama.value = '';
        inputNisn.value = '';
        inputAngkatan.value = '';
        inputJk.value = '';
        inputEmail.value = '';
        inputSekolah.value = '';
        inputPassword.value = '';

        modal.style.display = 'flex';
    }

    function openEditModal(id, nama, email, idSekolah, nisn, jk, angkatan) {
        title.innerText = 'Edit Informasi Akun Siswa';
        form.action = "/admin/siswa/" + id;
        methodInput.value = 'PUT';

        inputPassword.removeAttribute('required');
        passwordNote.style.display = 'block';

        
        inputNama.value = nama;
        inputNisn.value = nisn.startsWith('S') ? '' : nisn; 
        inputAngkatan.value = angkatan;
        inputJk.value = jk;
        inputEmail.value = email;
        inputSekolah.value = idSekolah;
        inputPassword.value = '';

        modal.style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection