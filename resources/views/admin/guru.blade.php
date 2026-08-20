@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-guru" style="animation: fadeIn 0.4s ease-in-out;">
    
    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border: 1px solid #34D399;">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color: #065F46; cursor:pointer; font-size: 20px; font-weight: bold;">&times;</button>
    </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Data Guru BK</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola akses dan akun pendidik dari seluruh sekolah mitra.</p>
        </div>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.guru') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin: 0;">
                <select name="filter_sekolah" onchange="this.form.submit()"
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 13px; background: var(--white); color: var(--ink); outline: none; cursor: pointer; min-width: 160px;">
                    <option value="all">Semua Sekolah</option>
                    @foreach($sekolahs as $sekolah)
                        <option value="{{ $sekolah->id_sekolah }}" {{ request('filter_sekolah') == $sekolah->id_sekolah ? 'selected' : '' }}>
                            {{ $sekolah->nama_sekolah }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari Gguru..." 
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 240px; outline: none; font-size: 13px;">
                
                <button type="submit" style="background: var(--ink); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">
                    Cari
                </button>
            </form>
            
            <button onclick="openAddModal()" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Tambah Akun Guru
            </button>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA LENGKAP / NIP</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">ASAL SEKOLAH</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">SISWA DIDIKAN</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; color: var(--ink-60);">TINDAKAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $guru)
                <tr style="border-top: 1px solid var(--cream);">
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 700; color: var(--ink); font-size: 14px;">{{ $guru->nama }}</div>
                        <div style="font-size: 12px; color: var(--ink-60); margin-top: 2px;">{{ $guru->email }}</div>
                        <div style="font-size: 11px; color: var(--ink-30); font-weight: 600; margin-top: 1px;">NIP: {{ optional($guru->guru)->nip ?? '-' }}</div>
                    </td>
                    <td style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: var(--ink);">
                        {{ optional($guru->sekolah)->nama_sekolah ?? 'Tidak Terikat' }}
                    </td>
                    <td style="padding: 20px 24px; font-size: 14px; color: var(--ink-60);">
                        @if($guru->sekolah)
                            {{ $guru->sekolah->users->where('id_role', 3)->count() }} Siswa
                        @else
                            0 Siswa
                        @endif
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <button onclick="openEditModal('{{ $guru->id }}', '{{ addslashes($guru->nama) }}', '{{ $guru->email }}', '{{ $guru->id_sekolah }}', '{{ optional($guru->guru)->nip }}')" style="background:none; border:none; color: var(--ink-60); font-weight: 600; font-size: 13px; cursor: pointer; padding:0;">
                                Edit
                            </button>
                            <span style="color: var(--cream);">|</span>
                            
                            <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $guru->nama }}? Akses masuk sistem akan dicabut.');" style="margin: 0;">
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
                    <td colspan="4" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                        Tidak ada data guru BK ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 13px; color: var(--ink-60);">
            Menampilkan {{ $gurus->firstItem() ?? 0 }} hingga {{ $gurus->lastItem() ?? 0 }} dari {{ $gurus->total() }} guru
        </div>
        <div>
            {{ $gurus->appends(request()->query())->links() }}
        </div>
    </div>

    <div id="guruModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: var(--white); width: 100%; max-width: 520px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; animation: fadeIn 0.3s ease-out;">

            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="modalTitle" style="font-size: 18px; font-weight: 700; color: var(--ink);">Tambah Akun Guru Baru</h3>
                <button onclick="closeModal('guruModal')" style="background: none; border: none; font-size: 24px; color: var(--ink-30); cursor: pointer;">&times;</button>
            </div>

            <form id="guruForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div style="padding: 32px; max-height: 70vh; overflow-y: auto; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Nama Lengkap (Beserta Gelar)</label>
                        <input type="text" id="inputNama" name="nama" required placeholder="Contoh: Ahmad Rizky, M.Pd." style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" id="inputNip" name="nip" required placeholder="Contoh: 19850312..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Alamat Email</label>
                        <input type="email" id="inputEmail" name="email" required placeholder="nama.guru@sekolah.sch.id" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Asal Sekolah Tugas</label>
                        <select id="inputSekolah" name="id_sekolah" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none; cursor: pointer;">
                            <option value="" disabled selected>Pilih Instansi Sekolah...</option>
                            @foreach($sekolahs as $sekolah)
                                <option value="{{ $sekolah->id_sekolah }}">{{ $sekolah->nama_sekolah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Kata Sandi Akses</label>
                        <input type="password" id="inputPassword" name="password" placeholder="Minimal 8 karakter" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                        <p id="passwordNote" style="font-size: 11px; color: var(--ink-60); margin-top: 6px; display: none;">*Kosongkan jika tidak ingin mengubah kata sandi lama.</p>
                    </div>
                </div>

                <div style="padding: 24px 32px; background: #FAFAF9; border-top: 1px solid var(--cream); display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeModal('guruModal')" style="padding: 12px 24px; background: transparent; border: 1px solid var(--ink-30); color: var(--ink-60); border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 12px 24px; background: var(--amber); border: none; color: white; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const modal = document.getElementById('guruModal');
    const form = document.getElementById('guruForm');
    const title = document.getElementById('modalTitle');
    const methodInput = document.getElementById('formMethod');
    
    const inputNama = document.getElementById('inputNama');
    const inputNip = document.getElementById('inputNip');
    const inputEmail = document.getElementById('inputEmail');
    const inputSekolah = document.getElementById('inputSekolah');
    const inputPassword = document.getElementById('inputPassword');
    const passwordNote = document.getElementById('passwordNote');

    function openAddModal() {
        title.innerText = 'Tambah Akun Guru Baru';
        form.action = "{{ route('admin.guru.store') }}";
        methodInput.value = 'POST';
        
        inputPassword.setAttribute('required', 'required');
        passwordNote.style.display = 'none';

        inputNama.value = '';
        inputNip.value = '';
        inputEmail.value = '';
        inputSekolah.value = '';
        inputPassword.value = '';

        modal.style.display = 'flex';
    }

    function openEditModal(id, nama, email, idSekolah, nip) {
        title.innerText = 'Edit Informasi Guru BK';
        form.action = "/admin/guru/" + id;
        methodInput.value = 'PUT';

        inputPassword.removeAttribute('required');
        passwordNote.style.display = 'block';

        inputNama.value = nama;
        inputNip.value = nip;
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