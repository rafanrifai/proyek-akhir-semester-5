@extends('layouts.app')

@section('title', 'Pusat Laporan BMN')

@section('content')
<style>
    .page-header {
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 0 0 4px 0;
    }
    .page-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
    }
    .report-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    @media (max-width: 900px) {
        .report-grid {
            grid-template-columns: 1fr;
        }
    }
    .report-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .report-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }
    .report-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 0 0 6px 0;
    }
    .report-card-desc {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0 0 20px 0;
    }
    .btn-action {
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Pusat Laporan & Ekspor BMN</h1>
    <p class="page-subtitle">Modul 8: Menghasilkan laporan data barang inventaris dan hasil klasifikasi Machine Learning untuk dicetak atau diekspor.</p>
</div>

<div class="report-grid">
    <!-- Card 1: Berita Acara (BA) Sensus -->
    <div class="report-card">
        <div>
            <div class="report-icon" style="background: var(--blue-light); color: var(--blue-accent);">
                <i class="ph ph-file-text"></i>
            </div>
            <h3 class="report-card-title">Berita Acara (BA) Sensus BMN</h3>
            <p class="report-card-desc">
                Format resmi surat Berita Acara hasil sensus fisik BMN lengkap dengan KOP Surat, tabel rincian, tanda tangan pejabat, dan arsip digital.
            </p>
        </div>
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <a href="{{ route('report.ba') }}" target="_blank" class="btn-action" style="background: var(--navy-card); color: white;">
                <i class="ph ph-printer"></i> Buka / Cetak Berita Acara
            </a>
            <a href="{{ route('report.archives') }}" class="btn-action" style="background: #F1F5F9; color: var(--text-dark);">
                <i class="ph ph-archive"></i> Buka Arsip Laporan
            </a>
        </div>
    </div>

    <!-- Card 2: Laporan Rekap Data BMN -->
    <div class="report-card">
        <div>
            <div class="report-icon" style="background: var(--success-bg); color: var(--success-text);">
                <i class="ph ph-package"></i>
            </div>
            <h3 class="report-card-title">Laporan Data Inventaris BMN</h3>
            <p class="report-card-desc">
                Rekapitulasi lengkap inventaris barang BMN, difilter menurut kondisi (Baik, Rusak Ringan, Rusak Sedang, Rusak Berat) serta unit kerja.
            </p>
            <form id="form_bmn_filter" style="margin-bottom: 16px;">
                <select name="kondisi" id="filter_kondisi_bmn" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; margin-bottom: 8px;">
                    <option value="">Semua Kondisi Barang</option>
                    <option value="BAIK">Kondisi Baik</option>
                    <option value="RUSAK_RINGAN">Kondisi Rusak Ringan</option>
                    <option value="RUSAK_SEDANG">Kondisi Rusak Sedang</option>
                    <option value="RUSAK_BERAT">Kondisi Rusak Berat</option>
                </select>
                @if(count($units) > 1)
                    <select name="unit_id" id="filter_unit_bmn" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px;">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                @endif
            </form>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="cetakBmn()" class="btn-action" style="flex: 1; background: var(--blue-accent); color: white; border: none;">
                <i class="ph ph-printer"></i> Cetak
            </button>
            <button onclick="exportBmn()" class="btn-action" style="flex: 1; background: #059669; color: white; border: none;">
                <i class="ph ph-file-csv"></i> Ekspor CSV
            </button>
        </div>
    </div>

    <!-- Card 3: Laporan Hasil Klasifikasi ML -->
    <div class="report-card">
        <div>
            <div class="report-icon" style="background: #FFEDD5; color: #EA580C;">
                <i class="ph ph-tree-structure"></i>
            </div>
            <h3 class="report-card-title">Laporan Hasil Klasifikasi (ML)</h3>
            <p class="report-card-desc">
                Laporan evaluasi tingkat kerusakan berbasis Decision Tree, ringkasan probabilitas/confidence, alur aturan, dan rekomendasi perbaikan/penghapusan.
            </p>
            <form id="form_klasifikasi_filter" style="margin-bottom: 16px;">
                <select name="hasil" id="filter_hasil_klasifikasi" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; margin-bottom: 8px;">
                    <option value="">Semua Kategori Hasil Prediksi</option>
                    <option value="BAIK">Prediksi Baik</option>
                    <option value="RUSAK_RINGAN">Prediksi Rusak Ringan</option>
                    <option value="RUSAK_SEDANG">Prediksi Rusak Sedang</option>
                    <option value="RUSAK_BERAT">Prediksi Rusak Berat</option>
                </select>
                @if(count($units) > 1)
                    <select name="unit_id" id="filter_unit_klasifikasi" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px;">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                @endif
            </form>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="cetakKlasifikasi()" class="btn-action" style="flex: 1; background: var(--blue-accent); color: white; border: none;">
                <i class="ph ph-printer"></i> Cetak
            </button>
            <button onclick="exportKlasifikasi()" class="btn-action" style="flex: 1; background: #059669; color: white; border: none;">
                <i class="ph ph-file-csv"></i> Ekspor CSV
            </button>
        </div>
    </div>
</div>

<script>
    function cetakBmn() {
        const kondisi = document.getElementById('filter_kondisi_bmn').value;
        const unitEl = document.getElementById('filter_unit_bmn');
        const unit = unitEl ? unitEl.value : '';
        window.open(`{{ route('report.cetak-bmn') }}?kondisi=${kondisi}&unit_id=${unit}`, '_blank');
    }

    function exportBmn() {
        const kondisi = document.getElementById('filter_kondisi_bmn').value;
        const unitEl = document.getElementById('filter_unit_bmn');
        const unit = unitEl ? unitEl.value : '';
        window.location.href = `{{ route('report.export-bmn') }}?kondisi=${kondisi}&unit_id=${unit}`;
    }

    function cetakKlasifikasi() {
        const hasil = document.getElementById('filter_hasil_klasifikasi').value;
        const unitEl = document.getElementById('filter_unit_klasifikasi');
        const unit = unitEl ? unitEl.value : '';
        window.open(`{{ route('report.cetak-klasifikasi') }}?hasil=${hasil}&unit_id=${unit}`, '_blank');
    }

    function exportKlasifikasi() {
        const hasil = document.getElementById('filter_hasil_klasifikasi').value;
        const unitEl = document.getElementById('filter_unit_klasifikasi');
        const unit = unitEl ? unitEl.value : '';
        window.location.href = `{{ route('report.export-klasifikasi') }}?hasil=${hasil}&unit_id=${unit}`;
    }
</script>
@endsection

