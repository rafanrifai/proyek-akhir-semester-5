@extends('layouts.app')

@section('title', 'Dasbor')

@section('content')
<style>
    /* Dashboard Specific Styles */
    .greeting-section {
        margin-bottom: 24px;
    }
    .greeting-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }
    .greeting-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
    }
    .date-badge {
        background-color: var(--blue-light);
        color: var(--blue-accent);
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .greeting-name {
        font-size: 24px;
        font-weight: 800;
        margin: 0 0 4px 0;
        line-height: 1.2;
    }
    .greeting-role {
        font-size: 12px;
        color: var(--blue-accent);
        font-weight: 500;
    }

    .unit-selector {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .unit-selector-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .unit-icon {
        width: 32px;
        height: 32px;
        background-color: var(--blue-light);
        color: var(--blue-accent);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .unit-text label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .unit-text span {
        font-size: 13px;
        font-weight: 600;
    }
    .unit-selector-right i {
        color: var(--blue-accent);
        font-weight: bold;
    }

    /* Main Card */
    .navy-card {
        background: linear-gradient(135deg, var(--navy-card), var(--navy-dark));
        border-radius: 16px;
        padding: 20px;
        color: white;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
    }
    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .card-label {
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #94A3B8;
    }
    .trend-badge {
        background-color: rgba(16, 185, 129, 0.2);
        color: #34D399;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 2px;
    }
    .card-value {
        font-size: 28px;
        font-weight: 800;
        margin: 0 0 4px 0;
    }
    .card-subtitle {
        font-size: 12px;
        color: #94A3B8;
        margin: 0;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    .stat-box {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .stat-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .stat-dot.success { background-color: var(--success-text); }
    .stat-dot.warning { background-color: var(--blue-accent); } /* Based on image */
    .stat-dot.danger { background-color: var(--danger-text); }
    
    .stat-badge {
        font-size: 9px;
        font-weight: 700;
        padding: 2px 4px;
        border-radius: 4px;
    }
    .stat-badge.success { background-color: var(--success-bg); color: var(--success-text); }
    .stat-badge.warning { background-color: var(--blue-light); color: var(--blue-accent); }
    .stat-badge.danger { background-color: var(--danger-bg); color: var(--danger-text); }
    
    .stat-value {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 2px 0;
    }
    .stat-label {
        font-size: 10px;
        color: var(--text-muted);
        margin: 0;
    }

    /* Section Title */
    .section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin: 0 0 12px 0;
    }

    /* Quick Actions */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 30px;
    }
    .action-btn {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 16px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        color: var(--text-dark);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .action-btn.active {
        background-color: var(--navy-card);
        color: white;
        border-color: var(--navy-card);
    }
    .action-btn i {
        font-size: 24px;
        color: var(--text-muted);
    }
    .action-btn.active i {
        color: #38BDF8;
    }
    .action-btn span {
        font-size: 11px;
        font-weight: 600;
        text-align: center;
    }

    /* Alerts */
    .alerts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .alerts-header .section-title {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text-dark);
        font-size: 14px;
        text-transform: none;
        letter-spacing: 0;
    }
    .alerts-header .section-title i {
        color: var(--danger-text);
        font-size: 18px;
    }
    .alert-badge-red {
        background-color: var(--danger-bg);
        color: var(--danger-text);
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .alert-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 30px;
    }
    .alert-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        gap: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .alert-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }
    .alert-icon.blue { background-color: var(--blue-light); color: var(--blue-accent); }
    .alert-icon.red { background-color: var(--danger-bg); color: var(--danger-text); }
    
    .alert-content {
        flex: 1;
    }
    .alert-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 4px;
    }
    .alert-title {
        font-size: 13px;
        font-weight: 600;
        margin: 0;
    }
    .alert-desc {
        font-size: 11px;
        color: var(--text-muted);
        margin: 0 0 10px 0;
        line-height: 1.4;
    }
    .alert-btn {
        background-color: var(--navy-card);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }
    .alert-btn.light {
        background-color: var(--blue-light);
        color: var(--blue-accent);
    }

    /* Progress List */
    .progress-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .progress-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .progress-number {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        background-color: var(--blue-light);
        color: var(--blue-accent);
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .progress-number.red {
        background-color: var(--danger-bg);
        color: var(--danger-text);
    }
    .progress-info {
        flex: 1;
    }
    .progress-title-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
    }
    .progress-title {
        font-size: 13px;
        font-weight: 600;
    }
    .progress-pct {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 10px;
    }
    .progress-pct.success { background-color: var(--success-bg); color: var(--success-text); }
    .progress-pct.danger { background-color: var(--danger-bg); color: var(--danger-text); }
    
    .progress-subtitle {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }
    .progress-subtitle.danger { color: var(--danger-text); }
    
    .progress-bar-bg {
        height: 4px;
        background-color: var(--border-light);
        border-radius: 2px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 2px;
    }
    .progress-bar-fill.success { background-color: var(--success-text); }
    .progress-bar-fill.danger { background-color: var(--danger-text); }

    /* Timeline */
    .timeline {
        padding: 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        margin-bottom: 30px;
    }
    .timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 20px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--success-text);
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: 3px;
        top: 16px;
        bottom: -20px;
        width: 2px;
        background-color: var(--border-light);
    }
    .timeline-item:last-child::after {
        display: none;
    }
    .timeline-item.dark::before { background-color: var(--navy-dark); }
    .timeline-item.blue::before { background-color: var(--blue-accent); }
    
    .timeline-content {
        font-size: 12px;
        line-height: 1.5;
        margin: 0 0 4px 0;
    }
    .timeline-meta {
        font-size: 10px;
        color: var(--text-muted);
    }
</style>

<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Ringkasan Eksekutif</span>
        <div class="date-badge">
            <i class="ph ph-calendar-blank"></i>
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMM YYYY') }}
        </div>
    </div>
    <h2 class="greeting-name">Halo, {{ explode(' ', Auth::user()->name)[0] }}...</h2>
    <div class="greeting-role">Admin Pengelola BMN Utama &bull; Kabupaten Minahasa</div>

    @if(session('success'))
        <div style="background-color: var(--success-bg); color: var(--success-text); padding: 10px 14px; border-radius: 10px; margin-top: 16px; font-size: 12px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->role === 'ADMIN')
        <form action="{{ route('dashboard.active-unit') }}" method="POST" class="unit-selector" style="position: relative; cursor: pointer;">
            @csrf
            <div class="unit-selector-left">
                <div class="unit-icon"><i class="ph ph-buildings"></i></div>
                <div class="unit-text">
                    <label>Satuan Kerja Aktif</label>
                    <span>{{ $activeUnitName }}</span>
                </div>
            </div>
            <div class="unit-selector-right">
                <i class="ph ph-caret-down"></i>
            </div>
            <select name="unit_id" aria-label="Pilih satuan kerja aktif" onchange="this.form.submit()" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                <option value="">Seluruh Wilayah</option>
                @foreach($activeUnits as $unit)
                    <option value="{{ $unit->id }}" {{ $activeUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                @endforeach
            </select>
        </form>
    @else
        <div class="unit-selector">
            <div class="unit-selector-left">
                <div class="unit-icon"><i class="ph ph-buildings"></i></div>
                <div class="unit-text">
                    <label>Satuan Kerja Aktif</label>
                    <span>{{ $activeUnitName }}</span>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="navy-card">
    <img src="{{ asset('images/logo.png') }}" alt="Minahasa" style="position: absolute; top: -10px; right: -10px; height: 160px; opacity: 0.15; filter: grayscale(100%) blur(0.5px); pointer-events: none; transform: rotate(-5deg); z-index: 0;">
    <div style="position: relative; z-index: 1;">
        <div class="card-top">
        <span class="card-label">
            <i class="ph ph-chart-line-up"></i>
            TOTAL NILAI KAPITALISASI BMN
        </span>
        <span class="trend-badge" style="background-color: rgba(255,255,255,0.1); color: white;"><i class="ph ph-wallet"></i> Aktif</span>
    </div>
    <h3 class="card-value">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
    <p class="card-subtitle">Total {{ number_format($totalItem, 0, ',', '.') }} item Tercatat dalam SIMAK-BMN</p>
    
        <!-- Dummy Chart Line using SVG -->
        <svg viewBox="0 0 100 20" style="width: 100%; height: 40px; margin-top: 10px; stroke: #38BDF8; stroke-width: 2; fill: none;">
            <path d="M0,15 L10,12 L20,16 L30,10 L40,14 L50,8 L60,12 L70,5 L80,10 L90,2 L100,6" />
        </svg>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-box">
        <div class="stat-top">
            <div class="stat-dot success"></div>
            <div class="stat-badge success">{{ $pctBaik }}%</div>
        </div>
        <h4 class="stat-value">{{ number_format($baik, 0, ',', '.') }}</h4>
        <p class="stat-label">Kondisi Baik</p>
    </div>
    <div class="stat-box">
        <div class="stat-top">
            <div class="stat-dot warning"></div>
            <div class="stat-badge warning">{{ $pctRusakRingan }}%</div>
        </div>
        <h4 class="stat-value">{{ number_format($rusakRingan, 0, ',', '.') }}</h4>
        <p class="stat-label">Rusak Ringan</p>
    </div>
    <div class="stat-box">
        <div class="stat-top">
            <div class="stat-dot danger"></div>
            <div class="stat-badge danger">{{ $pctRusakBerat }}%</div>
        </div>
        <h4 class="stat-value">{{ number_format($rusakBerat, 0, ',', '.') }}</h4>
        <p class="stat-label">Rusak Berat</p>
    </div>
</div>

<h3 class="section-title">Aksi Cepat Pengelolaan</h3>
<div class="actions-grid">
    <a href="#" class="action-btn active">
        <i class="ph ph-qr-code"></i>
        <span>Scan QR</span>
    </a>
    <a href="{{ route('bmn.create') }}" class="action-btn">
        <i class="ph ph-plus-square"></i>
        <span>Input BMN</span>
    </a>
    <a href="{{ route('sensus.index') }}" class="action-btn">
        <i class="ph ph-arrows-left-right"></i>
        <span>Sensus Fisik</span>
    </a>
    <a href="{{ route('report.ba') }}" class="action-btn">
        <i class="ph ph-printer"></i>
        <span>Cetak BA</span>
    </a>
</div>

<div class="alerts-header">
    <h3 class="section-title">
        <i class="ph ph-warning-circle"></i>
        Butuh Perhatian Segera
    </h3>
    <span class="alert-badge-red">3 Kasus</span>
</div>
<div class="alert-list">
    <div class="alert-card">
        <div class="alert-icon blue">
            <i class="ph ph-arrows-left-right"></i>
        </div>
        <div class="alert-content">
            <div class="alert-top">
                <h4 class="alert-title">Persetujuan Mutasi BMN</h4>
                <span class="alert-badge-red" style="background:#F1F5F9; color:#64748B;">6 Tiket</span>
            </div>
            <p class="alert-desc">Menunggu otorisasi Admin Kanwil untuk perpindahan unit kerja.</p>
            <button class="alert-btn">Review Berkas <i class="ph ph-caret-right"></i></button>
        </div>
    </div>
    
    <div class="alert-card">
        <div class="alert-icon red">
            <i class="ph ph-magnifying-glass"></i>
        </div>
        <div class="alert-content">
            <div class="alert-top">
                <h4 class="alert-title">18 Aset Belum Diinventarisasi</h4>
                <span class="alert-badge-red">Batas Dekat</span>
            </div>
            <p class="alert-desc">Jadwal batas validasi sensus semesteran tersisa 4 hari kerja.</p>
            <button class="alert-btn light"><i class="ph ph-paper-plane-right"></i> Kirim Notifikasi Petugas</button>
        </div>
    </div>
</div>

<div class="alerts-header" style="margin-top: 10px;">
    <h3 class="section-title" style="color:var(--text-dark); display:block; margin:0;">
        Progres Sensus {{ date('Y') }} Tiap Unit Kerja
        <span style="display:block; font-size:10px; color:var(--text-muted); font-weight:500; margin-top:2px;">Monitoring kepatuhan inventarisasi fisik BMN</span>
    </h3>
</div>
<div class="progress-list">
    @forelse($progressSensus as $index => $ps)
        @php
            // Menentukan warna berdasarkan persentase
            $colorClass = 'success';
            $bgStyle = '';
            $textStyle = '';
            if($ps->percentage < 50) {
                $colorClass = 'danger';
                $bgStyle = 'background:#FEE2E2; color:#DC2626;';
                $textStyle = 'color:#DC2626;';
            } elseif($ps->percentage < 80) {
                $colorClass = 'warning';
                $bgStyle = 'background:#FEF3C7; color:#D97706;';
                $textStyle = 'color:#D97706;';
            }
        @endphp
        <div class="progress-item">
            <div class="progress-number {{ $colorClass }}" style="{{ $bgStyle }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <div class="progress-info">
                <div class="progress-title-row">
                    <span class="progress-title">{{ $ps->unit_name }}</span>
                    <span class="progress-pct {{ $colorClass }}" style="{{ $bgStyle }}">{{ $ps->percentage }}%</span>
                </div>
                <div class="progress-subtitle" style="{{ $textStyle }}">{{ $ps->total_aset }} Aset Terdaftar &bull; {{ $ps->sensus_done }} Selesai</div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill {{ $colorClass }}" style="width: {{ $ps->percentage }}%; {{ $ps->percentage < 50 ? 'background-color:#DC2626;' : ($ps->percentage < 80 ? 'background-color:#D97706;' : '') }}"></div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 12px;">Belum ada data unit kerja / BMN.</div>
    @endforelse
</div>

<div class="alerts-header">
    <h3 class="section-title" style="color:var(--text-dark); margin:0;">Log Audit & Aktivitas Terkini</h3>
    <span style="font-size:10px; color:var(--text-muted); font-weight:600;">Real-time</span>
</div>
<div class="timeline">
    <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 12px;">
        Belum ada aktivitas yang direkam di sistem.
    </div>
</div>

@endsection
