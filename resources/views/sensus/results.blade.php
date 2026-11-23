@extends('layouts.app')

@section('title', 'Pilih Aset untuk Sensus')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Modul Verifikasi</span>
        <a href="{{ route('sensus.index') }}" style="background-color: white; border: 1px solid var(--border-light); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; color: var(--text-dark); text-decoration: none;">
            <i class="ph ph-arrow-left"></i> Pencarian Baru
        </a>
    </div>
    <h2 class="greeting-name">Pilih Aset untuk Disensus</h2>
    <div class="greeting-role">Ditemukan {{ $bmns->count() }} data dengan identitas yang sama</div>
</div>

<div style="background-color: var(--warning-bg); color: var(--warning-text); padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; font-weight: 600;">
    Beberapa data memiliki identitas yang sama. Periksa nama, merek, atau tahun perolehan, lalu pilih aset yang sesuai.
</div>

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background-color: var(--bg-gray); text-align: left; color: var(--text-muted); font-weight: 600;">
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Satker / Unit</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Kode Barang</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">NUP</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Nama / Merek</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Tahun</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bmns as $bmn)
                <tr style="border-bottom: 1px solid var(--border-light);">
                    <td style="padding: 12px 16px;"><strong>{{ $bmn->kode_satker ?? '-' }}</strong><br>{{ $bmn->unit->name ?? '-' }}</td>
                    <td style="padding: 12px 16px;">{{ $bmn->kode_barang }}</td>
                    <td style="padding: 12px 16px;">{{ $bmn->nup }}</td>
                    <td style="padding: 12px 16px;"><strong>{{ $bmn->name }}</strong><br>{{ $bmn->merk ?? '-' }}</td>
                    <td style="padding: 12px 16px;">{{ $bmn->tahun_perolehan ?? '-' }}</td>
                    <td style="padding: 12px 16px;">
                        <a href="{{ route('sensus.show', $bmn) }}" style="display: inline-flex; align-items: center; gap: 6px; background-color: var(--blue-accent); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 11px; font-weight: 700;">
                            <i class="ph ph-check-square-offset"></i> Sensus
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
