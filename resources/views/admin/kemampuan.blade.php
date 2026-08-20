@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-kemampuan" style="animation: fadeIn 0.4s ease-in-out;">

    @if(session('success'))
    <div style="background: #D1FAE5; color: #065F46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border: 1px solid #34D399;">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color: #065F46; cursor:pointer; font-size: 20px; font-weight: bold;">&times;</button>
    </div>
    @endif
    @if($errors->any())
    <div style="background: #FEF2F2; color: #991B1B; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border: 1px solid #F87171;">
        <span>{{ $errors->first() }}</span>
        <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color: #991B1B; cursor:pointer; font-size: 20px; font-weight: bold;">&times;</button>
    </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Data Indikator Kemampuan</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola parameter kompetensi bakat dan minat siswa (RIASEC) untuk mapping evaluasi.</p>
        </div>

        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.kemampuan') }}" method="GET" style="display: flex; gap: 12px; align-items: center; margin: 0;">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode atau indikator..."
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 240px; outline: none; font-size: 13px;">
                <button type="submit" style="background: var(--ink); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">
                    Cari
                </button>
            </form>

            <button onclick="openKemampuanModal()" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Tambah Indikator
            </button>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <!-- <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60); width: 80px;">ID</th> -->
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60); width: 120px;">KODE ITEM</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">PERNYATAAN / INDIKATOR KOMPETENSI</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; color: var(--ink-60);">TINDAKAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kemampuans as $kemampuan)
                <tr style="border-top: 1px solid var(--cream);">
                    <!-- <td style="padding: 20px 24px; font-size: 14px; color: var(--ink-60); font-weight: 600;">
                        #{{ $kemampuan->id_kemampuan }}
                    </td> -->
                    <td style="padding: 20px 24px;">
                        <span style="background: #F3F4F6; color: #374151; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 13px; border: 1px solid #E5E7EB;">
                            {{ $kemampuan->kode_item }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 500; color: var(--ink); font-size: 14px; line-height: 1.5;">{{ $kemampuan->nama_kemampuan }}</div>
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <button onclick="openKemampuanModal('{{ $kemampuan->id_kemampuan }}', '{{ $kemampuan->kode_item }}', '{{ addslashes($kemampuan->nama_kemampuan) }}')" style="background:none; border:none; color: var(--ink-60); font-weight: 600; font-size: 13px; cursor: pointer; padding:0;">
                                Edit
                            </button>
                            <span style="color: var(--cream);">|</span>

                            <form action="{{ route('admin.kemampuan.destroy', $kemampuan->id_kemampuan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus indikator {{ $kemampuan->kode_item }}? Pengisian kuisioner siswa pada bidang ini akan ikut terhapus.');" style="margin: 0;">
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
                        Tidak ada data indikator kemampuan ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 13px; color: var(--ink-60);">
            Menampilkan {{ $kemampuans->firstItem() ?? 0 }} hingga {{ $kemampuans->lastItem() ?? 0 }} dari {{ $kemampuans->total() }} data
        </div>
        <div>
            {{ $kemampuans->appends(request()->query())->links() }}
        </div>
    </div>

    <div id="kemampuanModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: var(--white); width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; animation: fadeIn 0.3s ease-out;">

            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="modalTitle" style="font-size: 18px; font-weight: 700; color: var(--ink);">Tambah Indikator Baru</h3>
                <button onclick="closeModal('kemampuanModal')" style="background: none; border: none; font-size: 24px; color: var(--ink-30); cursor: pointer;">&times;</button>
            </div>

            <form id="kemampuanForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div style="padding: 32px; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Kode Item</label>
                        <input type="text" id="inputKodeItem" name="kode_item" required placeholder="Contoh: R1" maxlength="10" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Pernyataan / Nama Indikator</label>
                        <textarea id="inputNamaKemampuan" name="nama_kemampuan" required placeholder="Masukkan pernyataan kuesioner..." rows="4" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                </div>

                <div style="padding: 24px 32px; background: #FAFAF9; border-top: 1px solid var(--cream); display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeModal('kemampuanModal')" style="padding: 12px 24px; background: transparent; border: 1px solid var(--ink-30); color: var(--ink-60); border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 12px 24px; background: var(--amber); border: none; color: white; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const modal = document.getElementById('kemampuanModal');
    const form = document.getElementById('kemampuanForm');
    const title = document.getElementById('modalTitle');
    const methodInput = document.getElementById('formMethod');
    const inputKodeItem = document.getElementById('inputKodeItem');
    const inputNamaKemampuan = document.getElementById('inputNamaKemampuan');

    function openKeepModal(id = null, kode_item = '', nama_kemampuan = '') {
        openKemampuanModal(id, kode_item, nama_kemampuan);
    }

    function openKemampuanModal(id = null, kode_item = '', nama_kemampuan = '') {
        const cleanKode = kode_item.replace('q_', '');

        if (id) {
            title.innerText = 'Edit Indikator Kemampuan';
            form.action = "/admin/kemampuan/" + id;
            methodInput.value = 'PUT';
            inputKodeItem.value = cleanKode; 
            inputNamaKemampuan.value = nama_kemampuan;
        } else {
            title.innerText = 'Tambah Indikator Baru';
            form.action = "{{ route('admin.kemampuan.store') }}";
            methodInput.value = 'POST';
            inputKodeItem.value = '';
            inputNamaKemampuan.value = '';
        }
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