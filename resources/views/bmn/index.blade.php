@extends('layouts.app')

@section('title', 'Data BMN')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Modul Penatausahaan</span>
    </div>
    <h2 class="greeting-name">Data BMN</h2>
    <div class="greeting-role">Daftar Barang Milik Negara</div>
</div>

@if(session('success'))
<div style="background-color: var(--success-bg); color: var(--success-text); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
    <form method="GET" action="{{ route('bmn.index') }}" style="display: flex; gap: 8px; flex: 1; max-width: 450px; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama/NUP/Kode..." style="flex: 1; min-width: 160px; padding: 8px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif;">
        <select name="kondisi" onchange="this.form.submit()" style="padding: 8px 10px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif;">
            <option value="">Semua Kondisi</option>
            <option value="BAIK" {{ request('kondisi') == 'BAIK' ? 'selected' : '' }}>Baik</option>
            <option value="RUSAK_RINGAN" {{ request('kondisi') == 'RUSAK_RINGAN' ? 'selected' : '' }}>Rusak Ringan</option>
            <option value="RUSAK_SEDANG" {{ request('kondisi') == 'RUSAK_SEDANG' ? 'selected' : '' }}>Rusak Sedang</option>
            <option value="RUSAK_BERAT" {{ request('kondisi') == 'RUSAK_BERAT' ? 'selected' : '' }}>Rusak Berat</option>
        </select>
        <button type="submit" style="background-color: var(--blue-light); color: var(--blue-accent); border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;"><i class="ph ph-magnifying-glass"></i></button>
        @if(request('search') || request('kondisi'))
            <a href="{{ route('bmn.index') }}" style="color: var(--text-muted); font-size: 11px; text-decoration: none; align-self: center;">Reset</a>
        @endif
    </form>
    
    <a href="{{ route('bmn.create') }}" style="background-color: var(--navy-card); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
        <i class="ph ph-plus"></i> Tambah BMN
    </a>
</div>

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background-color: var(--bg-gray); text-align: left; color: var(--text-muted); font-weight: 600;">
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">NUP / Nama Barang</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Unit</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Kondisi</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bmns as $b)
                <tr style="border-bottom: 1px solid var(--border-light);">
                    <td style="padding: 12px 16px;">
                        <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">{{ $b->name }}</div>
                        <div style="font-size: 10px; color: var(--text-muted); margin-bottom: 4px;">{{ $b->kode_barang }} - NUP: {{ $b->nup }}</div>
                        @if($b->kepemilikan == 'BMN')
                            <span style="background-color: var(--blue-light); color: var(--blue-accent); padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 700;">Aset Negara (BMN)</span>
                        @else
                            <span style="background-color: #f1f5f9; color: #475569; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 700;">Barang Pribadi</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px; color: var(--text-muted);">{{ $b->unit->name ?? '-' }}</td>
                    <td style="padding: 12px 16px;">
                        @if($b->kondisi == 'BAIK')
                            <span style="background-color: var(--success-bg); color: var(--success-text); padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">BAIK</span>
                        @elseif($b->kondisi == 'RUSAK_RINGAN')
                            <span style="background-color: var(--warning-bg); color: var(--warning-text); padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">RUSAK RINGAN</span>
                        @elseif($b->kondisi == 'RUSAK_SEDANG')
                            <span style="background-color: #FFEDD5; color: #EA580C; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">RUSAK SEDANG</span>
                        @else
                            <span style="background-color: var(--danger-bg); color: var(--danger-text); padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">RUSAK BERAT</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('bmn.show', $b->id) }}" style="color: var(--text-muted); text-decoration: none;" title="Lihat Detail & Riwayat"><i class="ph ph-eye"></i></a>
                            <a href="{{ route('bmn.edit', $b->id) }}" style="color: var(--blue-accent); text-decoration: none;" title="Edit Data"><i class="ph ph-pencil-simple"></i></a>
                            <form action="{{ route('bmn.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger-text); cursor: pointer; padding: 0;" title="Hapus"><i class="ph ph-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">Tidak ada data BMN.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="padding: 12px 16px; border-top: 1px solid var(--border-light);">
        {{ $bmns->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
