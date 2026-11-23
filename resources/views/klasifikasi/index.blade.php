@extends('layouts.app')

@section('title', 'Hasil dan Riwayat Klasifikasi BMN')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
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
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .stat-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .stat-card-title {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .stat-card-val {
        font-size: 20px;
        font-weight: 800;
        margin: 0;
    }
    .badge-cond {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        display: inline-block;
    }
    .badge-cond.BAIK { background-color: var(--success-bg); color: var(--success-text); }
    .badge-cond.RUSAK_RINGAN { background-color: #FEF3C7; color: #D97706; }
    .badge-cond.RUSAK_SEDANG { background-color: #FFEDD5; color: #EA580C; }
    .badge-cond.RUSAK_BERAT { background-color: var(--danger-bg); color: var(--danger-text); }

    .table-container {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }
    th {
        background-color: #F8FAFC;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-light);
    }
    td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Hasil & Riwayat Klasifikasi Machine Learning</h1>
        <p class="page-subtitle">Modul 7: Riwayat hasil prediksi tingkat kerusakan BMN menggunakan algoritma Decision Tree.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('klasifikasi.tree') }}" style="background-color: white; color: var(--navy-dark); border: 1px solid var(--border-light); padding: 10px 14px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-graph" style="font-size: 18px; color: var(--blue-accent);"></i> Struktur Pohon
        </a>
        <a href="{{ route('klasifikasi.create') }}" style="background-color: var(--navy-card); color: white; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Klasifikasi Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background-color: var(--success-bg); color: var(--success-text); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <i class="ph ph-check-circle" style="font-size: 18px;"></i> {{ session('success') }}
    </div>
@endif

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card-title">Total Klasifikasi</div>
        <div class="stat-card-val" style="color: var(--navy-dark);">{{ $totalPrediksi }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Hasil Baik</div>
        <div class="stat-card-val" style="color: var(--success-text);">{{ $prediksiBaik }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Rusak Ringan</div>
        <div class="stat-card-val" style="color: #D97706;">{{ $prediksiRingan }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Rusak Sedang</div>
        <div class="stat-card-val" style="color: #EA580C;">{{ $prediksiSedang }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Rusak Berat</div>
        <div class="stat-card-val" style="color: var(--danger-text);">{{ $prediksiBerat }}</div>
    </div>
</div>

<div style="background-color: white; border: 1px solid var(--border-light); border-radius: 12px; padding: 16px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <form method="GET" action="{{ route('klasifikasi.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang atau kode klasifikasi..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
        </div>
        <div style="min-width: 160px;">
            <select name="hasil" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                <option value="">Semua Hasil Prediksi</option>
                <option value="BAIK" {{ request('hasil') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                <option value="RUSAK_RINGAN" {{ request('hasil') == 'RUSAK_RINGAN' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="RUSAK_SEDANG" {{ request('hasil') == 'RUSAK_SEDANG' ? 'selected' : '' }}>Rusak Sedang</option>
                <option value="RUSAK_BERAT" {{ request('hasil') == 'RUSAK_BERAT' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>
        <div>
            <button type="submit" style="background-color: var(--blue-accent); color: white; border: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i class="ph ph-magnifying-glass"></i> Filter
            </button>
        </div>
        @if(request('search') || request('hasil'))
            <div>
                <a href="{{ route('klasifikasi.index') }}" style="color: var(--text-muted); font-size: 12px; text-decoration: none; padding: 10px;">Reset</a>
            </div>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Kode & Tanggal</th>
                <th>Nama Barang BMN</th>
                <th>Kriteria Input (Fitur)</th>
                <th>Hasil Prediksi (ML)</th>
                <th>Keyakinan</th>
                <th>Operator</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayats as $item)
                <tr>
                    <td style="white-space: nowrap;">
                        <span style="font-size: 11px; font-weight: 700; color: var(--blue-accent); background: var(--blue-light); padding: 2px 6px; border-radius: 4px;">
                            {{ $item->kode_klasifikasi }}
                        </span>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                            {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--navy-dark);">
                            {{ $item->nama_barang }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                            Unit: {{ $item->unit->name ?? '-' }}
                            @if($item->bmn)
                                | Kode BMN: {{ $item->bmn->kode_barang }}
                            @endif
                        </div>
                    </td>
                    <td style="font-size: 11px; line-height: 1.5; color: #475569;">
                        <div>• Usia: <strong>{{ $item->usia_barang }} Thn ({{ $item->kategori_usia }})</strong></div>
                        <div>• Fisik: <strong>{{ str_replace('_', ' ', $item->kondisi_fisik) }}</strong></div>
                        <div>• Biaya: <strong>{{ $item->estimasi_biaya }}</strong> | Intensitas: <strong>{{ $item->intensitas_pemakaian }}</strong></div>
                    </td>
                    <td>
                        <span class="badge-cond {{ $item->hasil_klasifikasi }}">
                            {{ str_replace('_', ' ', $item->hasil_klasifikasi) }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--navy-dark); font-size: 12px;">
                            {{ round($item->confidence * 100) }}%
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 12px; font-weight: 600; color: #334155;">{{ $item->user->name ?? 'User' }}</div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('klasifikasi.show', $item->id) }}" style="background-color: var(--blue-light); color: var(--blue-accent); padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Lihat Detail Hasil & Rekomendasi">
                                <i class="ph ph-eye"></i> Detail
                            </a>
                            <form action="{{ route('klasifikasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip hasil prediksi ini?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger-text); cursor: pointer; padding: 6px;" title="Hapus"><i class="ph ph-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="ph ph-tree-structure" style="font-size: 36px; display: block; margin-bottom: 8px; color: #CBD5E1;"></i>
                        Belum ada riwayat hasil klasifikasi Machine Learning.
                        <div style="margin-top: 8px;">
                            <a href="{{ route('klasifikasi.create') }}" style="color: var(--blue-accent); font-weight: 600; text-decoration: none;">Klik di sini untuk melakukan klasifikasi pertama</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $riwayats->links() }}
</div>
@endsection

