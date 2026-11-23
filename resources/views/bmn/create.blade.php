@extends('layouts.app')

@section('title', 'Tambah Data BMN')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Data BMN</span>
    </div>
    <h2 class="greeting-name">Tambah Aset Baru</h2>
</div>

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    
    @if ($errors->any())
        <div style="background-color: var(--danger-bg); color: var(--danger-text); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 12px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bmn.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
        @csrf
        
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Unit Kepemilikan</label>
            <select name="unit_id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;" required>
                <option value="">Pilih Unit...</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Kode Satker Pemilik / Asal Barang</label>
            <input type="text" name="kode_satker" value="{{ old('kode_satker') }}" placeholder="Masukkan kode satker asal barang" maxlength="50" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; box-sizing: border-box;" required>
        </div>
        
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Kategori BMN</label>
            <select name="kategori_id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;">
                <option value="">Tidak ada kategori khusus</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: 3.02.01.01.001" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;" required>
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">NUP (Nomor Urut Pendaftaran)</label>
                <input type="text" name="nup" value="{{ old('nup') }}" placeholder="Contoh: 001" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;" required>
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Nama Barang</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Laptop HP ProBook" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Merek / Tipe</label>
                <input type="text" name="merk" value="{{ old('merk') }}" placeholder="Contoh: HP ProBook 440 G8" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Tahun Perolehan</label>
                <input type="number" name="tahun_perolehan" value="{{ old('tahun_perolehan') }}" placeholder="2024" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Nilai Perolehan (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}" placeholder="15000000" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Kondisi Barang</label>
                <select name="kondisi" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;" required>
                    <option value="BAIK" {{ old('kondisi') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                    <option value="RUSAK_RINGAN" {{ old('kondisi') == 'RUSAK_RINGAN' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="RUSAK_SEDANG" {{ old('kondisi') == 'RUSAK_SEDANG' ? 'selected' : '' }}>Rusak Sedang</option>
                    <option value="RUSAK_BERAT" {{ old('kondisi') == 'RUSAK_BERAT' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Status Kepemilikan</label>
            <select name="kepemilikan" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;" required>
                <option value="BMN" {{ old('kepemilikan') == 'BMN' ? 'selected' : '' }}>Bukan Milik Sendiri (Aset Negara / BMN)</option>
                <option value="PRIBADI" {{ old('kepemilikan') == 'PRIBADI' ? 'selected' : '' }}>Milik Sendiri (Barang Pribadi)</option>
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Upload Foto Fisik Barang</label>
            <div style="border: 2px dashed var(--border-light); border-radius: 8px; padding: 20px; text-align: center; background-color: var(--bg-gray);">
                <i class="ph ph-camera" style="font-size: 24px; color: var(--text-muted); margin-bottom: 8px; display: block;"></i>
                <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif" style="font-size: 12px; color: var(--text-muted);">
                <div style="margin-top: 8px; font-size: 10px; color: var(--text-muted);">Format yang diterima: JPG, JPEG, PNG, GIF. Maksimal 5 MB.</div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
            <a href="{{ route('bmn.index') }}" style="padding: 10px 16px; border: 1px solid var(--border-light); border-radius: 8px; background-color: white; color: var(--text-dark); text-decoration: none; font-size: 12px; font-weight: 600;">Batal</a>
            <button type="submit" style="padding: 10px 20px; border: none; border-radius: 8px; background-color: var(--navy-card); color: white; font-size: 12px; font-weight: 600; cursor: pointer;">Simpan Data</button>
        </div>

    </form>
</div>
@endsection
