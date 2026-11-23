@extends('layouts.app')

@section('title', 'Riwayat Kerusakan BMN')

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
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) {
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
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 6px;
    }
    .stat-card-val {
        font-size: 22px;
        font-weight: 800;
        margin: 0;
    }
    .filter-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
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
    .badge-cond.BARU { background-color: var(--blue-light); color: var(--blue-accent); }

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
        <h1 class="page-title">Riwayat Kerusakan BMN</h1>
        <p class="page-subtitle">Modul 5: Menyimpan dan memantau rekaman kerusakan inventaris barang secara berkala.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('riwayat-kerusakan.create') }}" style="background-color: var(--navy-card); color: white; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Catat Kerusakan Baru
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
        <div class="stat-card-title">Total Rekam Kerusakan</div>
        <div class="stat-card-val" style="color: var(--navy-dark);">{{ $totalKerusakan }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Rusak Ringan</div>
        <div class="stat-card-val" style="color: #D97706;">{{ $rusakRingan }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Rusak Sedang</div>
        <div class="stat-card-val" style="color: #EA580C;">{{ $rusakSedang }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Rusak Berat</div>
        <div class="stat-card-val" style="color: var(--danger-text);">{{ $rusakBerat }}</div>
    </div>
</div>

<div class="filter-card">
    <form method="GET" action="{{ route('riwayat-kerusakan.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang, kode, atau merk..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
        </div>
        <div style="min-width: 160px;">
            <select name="kondisi" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                <option value="">Semua Kondisi</option>
                <option value="RUSAK_RINGAN" {{ request('kondisi') == 'RUSAK_RINGAN' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="RUSAK_SEDANG" {{ request('kondisi') == 'RUSAK_SEDANG' ? 'selected' : '' }}>Rusak Sedang</option>
                <option value="RUSAK_BERAT" {{ request('kondisi') == 'RUSAK_BERAT' ? 'selected' : '' }}>Rusak Berat</option>
                <option value="BAIK" {{ request('kondisi') == 'BAIK' ? 'selected' : '' }}>Diperbaiki (Baik)</option>
            </select>
        </div>
        <div>
            <button type="submit" style="background-color: var(--blue-accent); color: white; border: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i class="ph ph-magnifying-glass"></i> Filter
            </button>
        </div>
        @if(request('search') || request('kondisi'))
            <div>
                <a href="{{ route('riwayat-kerusakan.index') }}" style="color: var(--text-muted); font-size: 12px; text-decoration: none; padding: 10px;">Reset</a>
            </div>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Waktu Catatan</th>
                <th>Barang BMN</th>
                <th>Perubahan Kondisi</th>
                <th>Catatan Kerusakan</th>
                <th>Pelapor</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayats as $r)
                <tr>
                    <td style="color: var(--text-muted); white-space: nowrap;">
                        <strong>{{ $r->created_at->translatedFormat('d M Y') }}</strong>
                        <div style="font-size: 11px;">{{ $r->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <a href="{{ route('bmn.show', $r->bmn_id) }}" style="color: var(--navy-dark); font-weight: 700; text-decoration: none;">
                            {{ $r->bmn->name ?? 'Barang Terhapus' }}
                        </a>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                            Kode: {{ $r->bmn->kode_barang ?? '-' }} | {{ $r->bmn->unit->name ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span class="badge-cond {{ $r->kondisi_sebelumnya }}">{{ str_replace('_', ' ', $r->kondisi_sebelumnya) }}</span>
                            <i class="ph ph-arrow-right" style="color: var(--text-muted); font-size: 12px;"></i>
                            <span class="badge-cond {{ $r->kondisi_baru }}">{{ str_replace('_', ' ', $r->kondisi_baru) }}</span>
                        </div>
                    </td>
                    <td style="max-width: 280px;">
                        <div style="line-height: 1.4; color: var(--text-dark);">{{ $r->catatan ?: '-' }}</div>
                        @if($r->foto_bukti)
                            <a href="{{ asset('storage/' . $r->foto_bukti) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: var(--blue-accent); margin-top: 4px; text-decoration: none;">
                                <i class="ph ph-image"></i> Lihat Foto Bukti
                            </a>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #334155;">{{ $r->user->name ?? 'Sistem' }}</div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('klasifikasi.create', ['bmn_id' => $r->bmn_id]) }}" title="Uji Prediksi Decision Tree" style="background-color: var(--blue-light); color: var(--blue-accent); padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ph ph-tree-structure"></i> Klasifikasi
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="ph ph-wrench" style="font-size: 32px; display: block; margin-bottom: 8px; color: #CBD5E1;"></i>
                        Belum ada catatan riwayat kerusakan yang terdaftar.
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

