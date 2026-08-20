@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-sekolah" style="animation: fadeIn 0.4s ease-in-out;">

    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border: 1px solid #34D399;">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color: #065F46; cursor:pointer; font-size: 20px; font-weight: bold;">&times;</button>
    </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Data Mitra Sekolah</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola instansi dan pengguna platform LENTERA.</p>
        </div>

        <div style="display: flex; gap: 16px; align-items: center;">
            <input type="text" placeholder="Cari nama sekolah..."
                style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 240px; outline: none; font-size: 13px;">

            <button onclick="openAddModal()" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Tambah Mitra Baru
            </button>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">INFORMASI SEKOLAH</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">KODE LISENSI</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">TOTAL GURU</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">TOTAL SISWA</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">ALAMAT</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; color: var(--ink-60);">TINDAKAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sekolahs as $sekolah)
                <tr style="border-top: 1px solid var(--cream);">
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 700; color: var(--ink); font-size: 15px;">{{ $sekolah->nama_sekolah }}</div>
                        <div style="font-size: 12px; color: var(--ink-60); margin-top: 4px;">Terdaftar: {{ $sekolah->created_at->format('d M Y') }}</div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-size: 12px;">
                            <span style="display: block; font-weight: 700; color: var(--ink);">Guru:</span>
                            <span style="color: var(--amber);">{{ $sekolah->kode_lisensi }}</span>
                        </div>
                        <div style="font-size: 12px; margin-top: 4px;">
                            <span style="display: block; font-weight: 700; color: var(--ink);">Siswa:</span>
                            <span style="color: #10B981;">{{ $sekolah->kode_lisensi_siswa ?? '-' }}</span>
                        </div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--ink);">
                            {{ $sekolah->users->where('role.nama_role', 'guru')->count() }} Akun
                        </div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--ink);">
                            {{ $sekolah->users->where('role.nama_role', 'siswa')->count() }} Siswa
                        </div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-size: 12px; font-weight: 600; color: var(--ink);">
                            {{ $sekolah->alamat }}
                        </div>
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <button type="button" onclick="openEditModal('{{ $sekolah->id_sekolah }}', '{{ addslashes($sekolah->nama_sekolah) }}', '{{ addslashes($sekolah->alamat) }}')" style="background:none; border:none; color: var(--ink-60); font-weight: 600; font-size: 13px; cursor: pointer; padding:0;">
                                Edit
                            </button>

                            <span style="color: var(--cream);">|</span>

                            <form action="{{ route('admin.sekolah.destroy', $sekolah->id_sekolah) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $sekolah->nama_sekolah }}? Semua data terkait akan disembunyikan.');" style="margin: 0;">
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
                        Belum ada data mitra sekolah. Silakan tambahkan sekolah baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="sekolahModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: var(--white); width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; animation: fadeIn 0.3s ease-out;">

            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="modalTitle" style="font-size: 18px; font-weight: 700; color: var(--ink);">Tambah Mitra Baru</h3>
                <button onclick="closeModal('sekolahModal')" style="background: none; border: none; font-size: 24px; color: var(--ink-30); cursor: pointer;">&times;</button>
            </div>

            <form id="sekolahForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div style="padding: 32px;">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Nama Sekolah</label>
                        <input type="text" id="inputNama" name="nama_sekolah" required placeholder="Contoh: SMAN 1 Malang" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Alamat Lengkap</label>
                        <textarea id="inputAlamat" name="alamat" required rows="3" placeholder="Masukkan alamat lengkap..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none; resize: none;"></textarea>
                    </div>

                    <div id="lisensiNote" style="background: rgba(201, 123, 42, 0.1); padding: 16px; border-radius: 10px;">
                        <p style="font-size: 12px; color: var(--amber); margin: 0; line-height: 1.5;">
                            <strong>Catatan:</strong> Kode Lisensi akan dibuat otomatis saat pendaftaran. (Abaikan jika sedang mode Edit).
                        </p>
                    </div>
                    <div style="margin-top: 15px; padding: 10px; background: #FFF7ED; border-radius: 8px;" id="lisensiInfo">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: var(--amber);">
                            <input type="checkbox" name="reset_lisensi" value="1">
                            <span>Ganti/Reset Kode Lisensi (Generate Baru)</span>
                        </label>
                    </div>
                </div>

                <div style="padding: 24px 32px; background: #FAFAF9; border-top: 1px solid var(--cream); display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeModal('sekolahModal')" style="padding: 12px 24px; background: transparent; border: 1px solid var(--ink-30); color: var(--ink-60); border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 12px 24px; background: var(--amber); border: none; color: white; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const modal = document.getElementById('sekolahModal');
    const form = document.getElementById('sekolahForm');
    const title = document.getElementById('modalTitle');
    const methodInput = document.getElementById('formMethod');
    const inputNama = document.getElementById('inputNama');
    const inputAlamat = document.getElementById('inputAlamat');
    const lisensiNote = document.getElementById('lisensiNote');
    const lisensiInfo = document.getElementById('lisensiInfo');

    
    function openAddModal() {
        title.innerText = 'Tambah Mitra Baru';
        form.action = "{{ route('admin.sekolah.store') }}";
        methodInput.value = 'POST';
        lisensiNote.style.display = 'block'; 
        lisensiInfo.style.display = 'none'; 

        inputNama.value = '';
        inputAlamat.value = '';
        modal.style.display = 'flex';
    }

    
    function openEditModal(id, nama, alamat) {
        title.innerText = 'Edit Data Mitra';
        form.action = "/admin/sekolah/" + id; 
        methodInput.value = 'PUT'; 
        lisensiNote.style.display = 'none'; 
        lisensiInfo.style.display = 'block'; 

        inputNama.value = nama;
        inputAlamat.value = alamat;
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