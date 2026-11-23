@extends('layouts.app')

@section('title', 'Detail BMN - ' . $bmn->name)

@section('content')
<style>
    .detail-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }
    .badge-status {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-status.baik { background-color: var(--success-bg); color: var(--success-text); }
    .badge-status.rusak-ringan { background-color: #FEF3C7; color: #D97706; }
    .badge-status.rusak-sedang { background-color: #FFEDD5; color: #EA580C; }
    .badge-status.rusak-berat { background-color: var(--danger-bg); color: var(--danger-text); }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-top: 20px;
    }
    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    .info-item {
        background: #F8FAFC;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-light);
    }
    .info-label {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 14px;
        color: var(--text-dark);
        font-weight: 700;
    }
    .timeline {
        position: relative;
        padding-left: 24px;
        margin-top: 16px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-light);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-dot {
        position: absolute;
        left: -24px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--blue-accent);
    }
    .timeline-content {
        background: #F8FAFC;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 12px 14px;
    }
</style>

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
        <a href="{{ route('bmn.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar BMN
        </a>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--navy-dark); margin: 0;">{{ $bmn->name }}</h2>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('klasifikasi.create', ['bmn_id' => $bmn->id]) }}" style="background-color: var(--navy-card); color: white; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-tree-structure"></i> Uji Klasifikasi ML
        </a>
        <a href="{{ route('bmn.edit', $bmn->id) }}" style="background-color: var(--blue-light); color: var(--blue-accent); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-pencil-simple"></i> Edit BMN
        </a>
    </div>
</div>

<div class="detail-card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span style="font-size: 12px; font-weight: 700; color: var(--blue-accent); background: var(--blue-light); padding: 2px 8px; border-radius: 4px;">
                    Kode: {{ $bmn->kode_barang }}
                </span>
                <span style="font-size: 12px; font-weight: 700; color: var(--text-muted);">
                    NUP: {{ $bmn->nup ?? '-' }}
                </span>
                <span style="font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 600; background: #E2E8F0; color: #334155;">
                    {{ $bmn->kepemilikan ?? 'BMN' }}
                </span>
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: var(--navy-dark); margin: 0;">{{ $bmn->name }}</h3>
            @if($bmn->merk)
                <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Merk / Tipe: <strong>{{ $bmn->merk }}</strong></p>
            @endif
        </div>
        <div>
            @if($bmn->kondisi == 'BAIK')
                <span class="badge-status baik"><i class="ph ph-check-circle"></i> BAIK</span>
            @elseif($bmn->kondisi == 'RUSAK_RINGAN')
                <span class="badge-status rusak-ringan"><i class="ph ph-warning"></i> RUSAK RINGAN</span>
            @elseif($bmn->kondisi == 'RUSAK_SEDANG')
                <span class="badge-status rusak-sedang"><i class="ph ph-warning-circle"></i> RUSAK SEDANG</span>
            @else
                <span class="badge-status rusak-berat"><i class="ph ph-x-circle"></i> RUSAK BERAT</span>
            @endif
        </div>
    </div>

    @if($bmn->fotos->first())
        <div style="margin-top: 16px; text-align: center;">
            <img src="{{ asset('storage/' . $bmn->fotos->first()->file_path) }}" alt="{{ $bmn->name }}" style="max-height: 240px; border-radius: 12px; object-fit: contain; border: 1px solid var(--border-light);">
        </div>
    @endif

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Satuan Kerja / Unit</div>
            <div class="info-value">{{ $bmn->unit->name ?? '-' }} ({{ $bmn->kode_satker ?? '-' }})</div>
        </div>
        <div class="info-item">
            <div class="info-label">Kategori BMN</div>
            <div class="info-value">{{ $bmn->kategori->name ?? 'Belum Dikategorikan' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tahun Perolehan & Usia</div>
            <div class="info-value">
                {{ $bmn->tahun_perolehan ?? '-' }}
                @if($bmn->tahun_perolehan)
                    <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">
                        ({{ max(0, date('Y') - $bmn->tahun_perolehan) }} Tahun)
                    </span>
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Nilai / Harga Perolehan</div>
            <div class="info-value">Rp {{ number_format($bmn->harga ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Lokasi / Gedung</div>
            <div class="info-value">{{ $bmn->lokasi->name ?? 'Tidak Terdata' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Sensus Fisik Terakhir</div>
            <div class="info-value">
                @if($bmn->last_sensus_at)
                    {{ $bmn->last_sensus_at->translatedFormat('d F Y H:i') }}
                @else
                    <span style="color: #EF4444; font-size: 13px;">Belum Pernah Disensus</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modul 5: Riwayat Kerusakan & Perubahan Kondisi -->
<div class="detail-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="ph ph-clock-counter-clockwise" style="font-size: 20px; color: var(--blue-accent);"></i>
            <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Riwayat Kerusakan & Perubahan Kondisi</h3>
        </div>
        <a href="{{ route('riwayat-kerusakan.create', ['bmn_id' => $bmn->id]) }}" style="background-color: var(--blue-light); color: var(--blue-accent); padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px;">
            <i class="ph ph-plus"></i> Catat Kerusakan
        </a>
    </div>

    @if($bmn->riwayatKondisis->count() > 0)
        <div class="timeline">
            @foreach($bmn->riwayatKondisis as $riwayat)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 6px;">
                            <span style="font-size: 11px; font-weight: 600; color: var(--text-muted);">
                                {{ $riwayat->created_at->translatedFormat('d F Y, H:i') }}
                            </span>
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 11px;">
                                <span style="font-weight: 600; color: #64748B;">{{ str_replace('_', ' ', $riwayat->kondisi_sebelumnya) }}</span>
                                <i class="ph ph-arrow-right"></i>
                                @if($riwayat->kondisi_baru == 'BAIK')
                                    <span class="badge-status baik">BAIK</span>
                                @elseif($riwayat->kondisi_baru == 'RUSAK_RINGAN')
                                    <span class="badge-status rusak-ringan">RUSAK RINGAN</span>
                                @elseif($riwayat->kondisi_baru == 'RUSAK_SEDANG')
                                    <span class="badge-status rusak-sedang">RUSAK SEDANG</span>
                                @else
                                    <span class="badge-status rusak-berat">RUSAK BERAT</span>
                                @endif
                            </div>
                        </div>
                        <p style="font-size: 13px; color: var(--text-dark); margin: 0; line-height: 1.5;">
                            {{ $riwayat->catatan ?: 'Tidak ada catatan tambahan.' }}
                        </p>
                        <div style="margin-top: 6px; font-size: 11px; color: var(--text-muted);">
                            Dicatat oleh: <strong>{{ $riwayat->user->name ?? 'Sistem' }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 13px;">
            <i class="ph ph-shield-check" style="font-size: 36px; color: var(--success-text); display: block; margin-bottom: 8px;"></i>
            Belum ada catatan riwayat kerusakan pada barang ini.
        </div>
    @endif
</div>
@endsection

