@extends('layouts.app')

@section('title', 'Catat Kerusakan BMN')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('riwayat-kerusakan.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali ke Riwayat Kerusakan
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--navy-dark); margin: 0;">Catat Kerusakan / Perubahan Kondisi</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Dokumentasikan kerusakan atau pembaharuan kondisi fisik barang BMN.</p>
    </div>

    @if($errors->any())
        <div style="background-color: var(--danger-bg); color: var(--danger-text); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background-color: white; border: 1px solid var(--border-light); border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <form action="{{ route('riwayat-kerusakan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Pilih Barang BMN <span style="color: var(--danger-text);">*</span></label>
                <select name="bmn_id" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                    <option value="">-- Pilih Barang BMN --</option>
                    @foreach($bmns as $b)
                        <option value="{{ $b->id }}" {{ (old('bmn_id', $selectedBmnId) == $b->id) ? 'selected' : '' }}>
                            {{ $b->name }} (Kode: {{ $b->kode_barang }} - Kondisi Saat Ini: {{ str_replace('_', ' ', $b->kondisi) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Kondisi Baru yang Dilaporkan <span style="color: var(--danger-text);">*</span></label>
                <select name="kondisi_baru" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                    <option value="RUSAK_RINGAN" {{ old('kondisi_baru') == 'RUSAK_RINGAN' ? 'selected' : '' }}>Rusak Ringan (Kerusakan kecil, fungsi utama masih berjalan)</option>
                    <option value="RUSAK_SEDANG" {{ old('kondisi_baru') == 'RUSAK_SEDANG' ? 'selected' : '' }}>Rusak Sedang (Kerusakan signifikan pada sebagian fungsi/komponen)</option>
                    <option value="RUSAK_BERAT" {{ old('kondisi_baru') == 'RUSAK_BERAT' ? 'selected' : '' }}>Rusak Berat (Mati total / tidak dapat digunakan kembali)</option>
                    <option value="BAIK" {{ old('kondisi_baru') == 'BAIK' ? 'selected' : '' }}>Kondisi Baik (Sudah selesai diperbaiki)</option>
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Catatan / Uraian Kerusakan <span style="color: var(--danger-text);">*</span></label>
                <textarea name="catatan" rows="4" required placeholder="Jelaskan bagian komponen yang rusak, kronologi atau penyebab kerusakan, kendala operasional, dll." style="width: 100%; padding: 12px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; box-sizing: border-box;">{{ old('catatan') }}</textarea>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Foto Bukti Fisik Kerusakan (Opsional)</label>
                <input type="file" name="foto_bukti" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed var(--border-light); border-radius: 8px; font-size: 12px;">
                <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 4px;">Format JPG, PNG, atau WEBP, maks. 5MB.</span>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('riwayat-kerusakan.index') }}" style="padding: 11px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark); background-color: #F1F5F9; text-decoration: none;">Batal</a>
                <button type="submit" style="background-color: var(--blue-accent); color: white; border: none; padding: 11px 22px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="ph ph-floppy-disk"></i> Simpan Catatan Kerusakan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

