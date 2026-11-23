@extends('layouts.app')

@section('title', 'Klasifikasi Tingkat Kerusakan BMN')

@section('content')
<style>
    .form-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 6px;
    }
    .form-hint {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
        display: block;
    }
    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--blue-accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .feature-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="max-width: 760px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('klasifikasi.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali ke Riwayat Klasifikasi
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--navy-dark); margin: 0;">Klasifikasi Machine Learning (Decision Tree)</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Modul 6: Prediksi tingkat kerusakan BMN berdasarkan parameter data masukan menggunakan pohon keputusan.</p>
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

    <div class="form-card">
        <form action="{{ route('klasifikasi.store') }}" method="POST">
            @csrf

            <div class="form-group" style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: 12px; padding: 16px;">
                <label class="form-label" style="display: flex; align-items: center; gap: 6px;">
                    <i class="ph ph-cube" style="font-size: 16px; color: var(--blue-accent);"></i> Hubungkan dengan Barang BMN (Opsional)
                </label>
                <select name="bmn_id" id="bmn_select" class="form-control" onchange="handleBmnSelect(this)">
                    <option value="">-- Input Bebas / Barang Tidak Ada di Database --</option>
                    @foreach($bmns as $b)
                        @php
                            $usia = $b->tahun_perolehan ? max(0, date('Y') - $b->tahun_perolehan) : 0;
                            $damageCount = $b->riwayatKondisis->whereIn('kondisi_baru', ['RUSAK_RINGAN', 'RUSAK_SEDANG', 'RUSAK_BERAT'])->count();
                            $rwVal = ($damageCount === 0) ? 'TIDAK_ADA' : (($damageCount <= 2) ? '1_2_KALI' : 'LEBIH_2_KALI');
                        @endphp
                        <option value="{{ $b->id }}" 
                            data-name="{{ $b->name }}"
                            data-usia="{{ $usia }}"
                            data-riwayat="{{ $rwVal }}"
                            {{ (old('bmn_id', $selectedBmn?->id) == $b->id) ? 'selected' : '' }}>
                            {{ $b->name }} (Kode: {{ $b->kode_barang }} - {{ $b->unit->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <span class="form-hint">Memilih barang BMN akan otomatis mengisi nama barang, masa pakai tahun, dan riwayat kerusakan sebelumnya.</span>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Barang / Aset <span style="color: var(--danger-text);">*</span></label>
                <input type="text" name="nama_barang" id="input_nama_barang" class="form-control" value="{{ old('nama_barang', $selectedBmn?->name) }}" required placeholder="Contoh: Laptop Acer TravelMate P214">
            </div>

            <div class="feature-grid">
                <!-- Fitur 1: Usia Barang -->
                <div class="form-group">
                    <label class="form-label">1. Usia Pemakaian Barang (Tahun) <span style="color: var(--danger-text);">*</span></label>
                    <input type="number" name="usia_barang" id="input_usia_barang" min="0" max="50" class="form-control" value="{{ old('usia_barang', $suggestedUsia ?? 1) }}" required>
                    <span class="form-hint">Kategori pohon: &le;3 Thn (Baru), 4-6 Thn (Sedang), &gt;6 Thn (Tua).</span>
                </div>

                <!-- Fitur 2: Intensitas Pemakaian -->
                <div class="form-group">
                    <label class="form-label">2. Intensitas Pemakaian <span style="color: var(--danger-text);">*</span></label>
                    <select name="intensitas_pemakaian" class="form-control" required>
                        <option value="RENDAH" {{ old('intensitas_pemakaian') == 'RENDAH' ? 'selected' : '' }}>Rendah (Jarang digunakan / cadangan)</option>
                        <option value="SEDANG" {{ old('intensitas_pemakaian', 'SEDANG') == 'SEDANG' ? 'selected' : '' }}>Sedang (Pemakaian rutin harian kantor)</option>
                        <option value="TINGGI" {{ old('intensitas_pemakaian') == 'TINGGI' ? 'selected' : '' }}>Tinggi (Operasional terus menerus / beban berat)</option>
                    </select>
                    <span class="form-hint">Seberapa sering barang dioperasikan oleh unit.</span>
                </div>
            </div>

            <div class="feature-grid">
                <!-- Fitur 3: Kondisi Fisik -->
                <div class="form-group">
                    <label class="form-label">3. Kondisi Kerusakan Fisik <span style="color: var(--danger-text);">*</span></label>
                    <select name="kondisi_fisik" class="form-control" required>
                        <option value="BAIK" {{ old('kondisi_fisik') == 'BAIK' ? 'selected' : '' }}>Baik (Semua komponen utuh & berfungsi normal)</option>
                        <option value="CACAT_RINGAN" {{ old('kondisi_fisik') == 'CACAT_RINGAN' ? 'selected' : '' }}>Cacat Ringan (Baret, kotor, baut kendor, fungsi aman)</option>
                        <option value="RUSAK_SEBAGIAN" {{ old('kondisi_fisik') == 'RUSAK_SEBAGIAN' ? 'selected' : '' }}>Rusak Sebagian (Ada komponen utama aus/bermasalah)</option>
                        <option value="RUSAK_TOTAL" {{ old('kondisi_fisik') == 'RUSAK_TOTAL' ? 'selected' : '' }}>Rusak Total (Mati total / hancur / tidak bisa menyala)</option>
                    </select>
                    <span class="form-hint">Pemeriksaan visual & fungsional fisik saat ini.</span>
                </div>

                <!-- Fitur 4: Estimasi Biaya Perbaikan -->
                <div class="form-group">
                    <label class="form-label">4. Estimasi Biaya Perbaikan <span style="color: var(--danger-text);">*</span></label>
                    <select name="estimasi_biaya" class="form-control" required>
                        <option value="KECIL" {{ old('estimasi_biaya', 'KECIL') == 'KECIL' ? 'selected' : '' }}>Kecil (&lt; 15% dari harga barang)</option>
                        <option value="SEDANG" {{ old('estimasi_biaya') == 'SEDANG' ? 'selected' : '' }}>Sedang (15% - 40% dari harga barang)</option>
                        <option value="BESAR" {{ old('estimasi_biaya') == 'BESAR' ? 'selected' : '' }}>Besar (&gt; 40% dari harga barang)</option>
                    </select>
                    <span class="form-hint">Perkiraan biaya servis/suku cadang vs harga perolehan.</span>
                </div>
            </div>

            <!-- Fitur 5: Riwayat Kerusakan -->
            <div class="form-group">
                <label class="form-label">5. Riwayat Kerusakan Sebelumnya <span style="color: var(--danger-text);">*</span></label>
                <select name="riwayat_kerusakan" id="input_riwayat" class="form-control" required>
                    <option value="TIDAK_ADA" {{ old('riwayat_kerusakan', $suggestedRiwayat) == 'TIDAK_ADA' ? 'selected' : '' }}>Tidak Ada (Belum pernah rusak / riwayat bersih)</option>
                    <option value="1_2_KALI" {{ old('riwayat_kerusakan', $suggestedRiwayat) == '1_2_KALI' ? 'selected' : '' }}>1 - 2 Kali (Pernah diservis sebelumnya)</option>
                    <option value="LEBIH_2_KALI" {{ old('riwayat_kerusakan', $suggestedRiwayat) == 'LEBIH_2_KALI' ? 'selected' : '' }}>Lebih dari 2 Kali (Sering mengalami kendala)</option>
                </select>
                <span class="form-hint">Frekuensi kerusakan yang pernah tercatat.</span>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="3" class="form-control" placeholder="Contoh: Gejala panas berlebih, layar flicker, pernah jatuh...">{{ old('catatan') }}</textarea>
            </div>

            <div class="form-group" style="background: var(--blue-light); border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin: 0;">
                    <input type="checkbox" name="update_kondisi_bmn" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                    <div>
                        <span style="font-size: 13px; font-weight: 700; color: var(--navy-dark); display: block;">Perbarui kondisi master data BMN secara otomatis</span>
                        <span style="font-size: 11px; color: var(--text-muted); display: block;">Jika dicentang dan barang terhubung, status kondisi BMN & riwayat kerusakan akan langsung terupdate dengan hasil klasifikasi ini.</span>
                    </div>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                <a href="{{ route('klasifikasi.index') }}" style="padding: 12px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--text-dark); background-color: #F1F5F9; text-decoration: none;">Batal</a>
                <button type="submit" style="background-color: var(--navy-card); color: white; border: none; padding: 12px 26px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-tree-structure" style="font-size: 18px; color: #60A5FA;"></i> Jalankan Klasifikasi Decision Tree
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleBmnSelect(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        const name = option.getAttribute('data-name');
        const usia = option.getAttribute('data-usia');
        const riwayat = option.getAttribute('data-riwayat');

        if (name) document.getElementById('input_nama_barang').value = name;
        if (usia !== null) document.getElementById('input_usia_barang').value = usia;
        if (riwayat) document.getElementById('input_riwayat').value = riwayat;
    }
</script>
@endsection

