@extends('layouts.admin')

@section('dashboard_content')
<div class="admin-mapel" style="animation: fadeIn 0.4s ease-in-out;">
    
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
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--ink);">Data Mata Pelajaran</h2>
            <p style="font-size: 14px; color: var(--ink-60);">Kelola daftar mata pelajaran yang akan dievaluasi oleh sistem.</p>
        </div>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.mapel') }}" method="GET" style="display: flex; gap: 12px; align-items: center; margin: 0;">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama mapel..." 
                    style="padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; width: 240px; outline: none; font-size: 13px;">
                <button type="submit" style="background: var(--ink); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">
                    Cari
                </button>
            </form>
            
            <button onclick="openMapelModal()" style="background: var(--amber); color: var(--white); border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Tambah Mapel
            </button>
        </div>
    </div>

    <div style="background: var(--white); border-radius: 16px; border: 1px solid rgba(171, 168, 159, 0.25); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--cream);">
                <tr>
                    <!-- <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60); width: 80px;">ID</th> -->
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; color: var(--ink-60);">NAMA MATA PELAJARAN</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; color: var(--ink-60);">TINDAKAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapels as $mapel)
                <tr style="border-top: 1px solid var(--cream);">
                    <!-- <td style="padding: 20px 24px; font-size: 14px; color: var(--ink-60); font-weight: 600;">
                        #{{ $mapel->id_mapel }}
                    </td> -->
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 700; color: var(--ink); font-size: 15px;">{{ $mapel->nama_mapel }}</div>
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <button onclick="openMapelModal('{{ $mapel->id_mapel }}', '{{ addslashes($mapel->nama_mapel) }}')" style="background:none; border:none; color: var(--ink-60); font-weight: 600; font-size: 13px; cursor: pointer; padding:0;">
                                Edit
                            </button>
                            <span style="color: var(--cream);">|</span>
                            
                            <form action="{{ route('admin.mapel.destroy', $mapel->id_mapel) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Mapel {{ $mapel->nama_mapel }}? Penghapusan ini dapat mempengaruhi data nilai siswa.');" style="margin: 0;">
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
                    <td colspan="3" style="padding: 32px; text-align: center; color: var(--ink-60); font-size: 14px;">
                        Tidak ada data mata pelajaran ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 13px; color: var(--ink-60);">
            Menampilkan {{ $mapels->firstItem() ?? 0 }} hingga {{ $mapels->lastItem() ?? 0 }} dari {{ $mapels->total() }} data
        </div>
        <div>
            {{ $mapels->appends(request()->query())->links() }}
        </div>
    </div>

    <div id="mapelModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: var(--white); width: 100%; max-width: 420px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; animation: fadeIn 0.3s ease-out;">

            <div style="padding: 24px 32px; border-bottom: 1px solid var(--cream); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="modalTitle" style="font-size: 18px; font-weight: 700; color: var(--ink);">Tambah Mapel Baru</h3>
                <button onclick="closeModal('mapelModal')" style="background: none; border: none; font-size: 24px; color: var(--ink-30); cursor: pointer;">&times;</button>
            </div>

            <form id="mapelForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div style="padding: 32px; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Nama Mata Pelajaran</label>
                        <input type="text" id="inputNamaMapel" name="nama_mapel" required placeholder="Contoh: Matematika" style="width: 100%; padding: 12px 16px; border: 1px solid var(--ink-30); border-radius: 10px; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="padding: 24px 32px; background: #FAFAF9; border-top: 1px solid var(--cream); display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeModal('mapelModal')" style="padding: 12px 24px; background: transparent; border: 1px solid var(--ink-30); color: var(--ink-60); border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 12px 24px; background: var(--amber); border: none; color: white; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const modal = document.getElementById('mapelModal');
    const form = document.getElementById('mapelForm');
    const title = document.getElementById('modalTitle');
    const methodInput = document.getElementById('formMethod');
    const inputNamaMapel = document.getElementById('inputNamaMapel');

    // Buka Modal Tambah
    function openMapelModal(id = null, nama_mapel = '') {
        if(id) {
            title.innerText = 'Edit Mata Pelajaran';
            form.action = "/admin/mapel/" + id;
            methodInput.value = 'PUT';
            inputNamaMapel.value = nama_mapel;
        } else {
            title.innerText = 'Tambah Mapel Baru';
            form.action = "{{ route('admin.mapel.store') }}";
            methodInput.value = 'POST';
            inputNamaMapel.value = '';
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