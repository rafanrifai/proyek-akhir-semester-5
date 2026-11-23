@extends('layouts.app')

@section('title', 'Manajemen Cabang (Unit)')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Manajemen Organisasi</span>
    </div>
    <h2 class="greeting-name">Daftar Cabang & Unit</h2>
    <div class="greeting-role">Kelola Struktur Unit Kerja Instansi</div>
</div>

@if(session('success'))
<div style="background-color: var(--success-bg); color: var(--success-text); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 16px;">
    <a href="{{ route('units.create') }}" style="background-color: var(--navy-card); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
        <i class="ph ph-plus"></i> Tambah Unit
    </a>
</div>

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background-color: var(--bg-gray); text-align: left; color: var(--text-muted); font-weight: 600;">
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Kode Satker</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Nama Unit / Cabang</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Tipe</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Induk (Utama)</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                <tr style="border-bottom: 1px solid var(--border-light);">
                    <td style="padding: 12px 16px;"><strong>{{ $unit->code }}</strong></td>
                    <td style="padding: 12px 16px; color: var(--text-dark); font-weight: 600;">{{ $unit->name }}</td>
                    <td style="padding: 12px 16px;">
                        @if($unit->type == 'UTAMA')
                            <span style="background-color: #FEF3C7; color: #D97706; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">UTAMA</span>
                        @else
                            <span style="background-color: #E0E7FF; color: #4338CA; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">CABANG</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px; color: var(--text-muted);">{{ $unit->parent ? $unit->parent->name : '-' }}</td>
                    <td style="padding: 12px 16px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('units.edit', $unit->id) }}" style="color: var(--blue-accent); text-decoration: none;"><i class="ph ph-pencil-simple"></i></a>
                            <form action="{{ route('units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus unit ini?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger-text); cursor: pointer; padding: 0;"><i class="ph ph-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: var(--text-muted);">Belum ada data unit/cabang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="padding: 12px 16px; border-top: 1px solid var(--border-light);">
        {{ $units->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
