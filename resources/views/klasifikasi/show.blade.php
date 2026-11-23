@extends('layouts.app')

@section('title', 'Hasil Klasifikasi - ' . $klasifikasi->kode_klasifikasi)

@section('content')
<style>
    .result-hero {
        border-radius: 16px;
        padding: 28px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .result-hero.BAIK { background: linear-gradient(135deg, #065F46, #047857); }
    .result-hero.RUSAK_RINGAN { background: linear-gradient(135deg, #92400E, #B45309); }
    .result-hero.RUSAK_SEDANG { background: linear-gradient(135deg, #9A3412, #C2410C); }
    .result-hero.RUSAK_BERAT { background: linear-gradient(135deg, #991B1B, #B91C1C); }

    .result-card {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
    .param-box {
        background: #F8FAFC;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 12px 14px;
    }
    .param-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .param-val {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-dark);
    }
    .tree-step {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #F8FAFC;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 8px;
    }
</style>

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
        <a href="{{ route('klasifikasi.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali ke Riwayat Klasifikasi
        </a>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--navy-dark); margin: 0;">Hasil Prediksi Decision Tree</h2>
    </div>
    <div style="display: flex; gap: 8px;">
        <button onclick="window.print()" style="background-color: white; border: 1px solid var(--border-light); color: var(--navy-dark); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-printer"></i> Cetak Hasil
        </button>
        <a href="{{ route('klasifikasi.create') }}" style="background-color: var(--navy-card); color: white; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="ph ph-plus"></i> Klasifikasi Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background-color: var(--success-bg); color: var(--success-text); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <i class="ph ph-check-circle" style="font-size: 18px;"></i> {{ session('success') }}
    </div>
@endif

<!-- Hero Card Hasil Klasifikasi -->
<div class="result-hero {{ $klasifikasi->hasil_klasifikasi }}">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
        <div>
            <span style="background: rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px; display: inline-block; margin-bottom: 8px;">
                KODE: {{ $klasifikasi->kode_klasifikasi }}
            </span>
            <h1 style="font-size: 26px; font-weight: 800; margin: 0 0 6px 0; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                {{ $klasifikasi->nama_barang }}
            </h1>
            <p style="font-size: 13px; opacity: 0.9; margin: 0;">
                Unit: {{ $klasifikasi->unit->name ?? '-' }} | Dievaluasi pada {{ $klasifikasi->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>
        <div style="text-align: right; background: rgba(0,0,0,0.15); padding: 14px 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.15);">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; opacity: 0.85; margin-bottom: 4px;">Kategori Kerusakan (ML)</div>
            <div style="font-size: 24px; font-weight: 800; letter-spacing: 0.5px;">
                {{ str_replace('_', ' ', $klasifikasi->hasil_klasifikasi) }}
            </div>
            <div style="font-size: 11px; margin-top: 4px; opacity: 0.9;">
                Tingkat Keyakinan: <strong>{{ round($klasifikasi->confidence * 100) }}%</strong>
            </div>
        </div>
    </div>
</div>

<!-- Parameter Input Masukan -->
<div class="result-card">
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
        <i class="ph ph-sliders-horizontal" style="font-size: 20px; color: var(--blue-accent);"></i>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Parameter Kriteria Masukan (Fitur)</h3>
    </div>
    <div class="grid-2">
        <div class="param-box">
            <div class="param-title">1. Usia Pemakaian Barang</div>
            <div class="param-val">{{ $klasifikasi->usia_barang }} Tahun (Kategori: {{ $klasifikasi->kategori_usia }})</div>
        </div>
        <div class="param-box">
            <div class="param-title">2. Intensitas Pemakaian</div>
            <div class="param-val">{{ $klasifikasi->intensitas_pemakaian }}</div>
        </div>
        <div class="param-box">
            <div class="param-title">3. Kondisi Kerusakan Fisik</div>
            <div class="param-val">{{ str_replace('_', ' ', $klasifikasi->kondisi_fisik) }}</div>
        </div>
        <div class="param-box">
            <div class="param-title">4. Estimasi Biaya Perbaikan</div>
            <div class="param-val">Kategori {{ $klasifikasi->estimasi_biaya }}</div>
        </div>
        <div class="param-box">
            <div class="param-title">5. Riwayat Kerusakan Sebelumnya</div>
            <div class="param-val">{{ str_replace('_', ' ', $klasifikasi->riwayat_kerusakan) }}</div>
        </div>
        <div class="param-box">
            <div class="param-title">Operator / Penguji</div>
            <div class="param-val">{{ $klasifikasi->user->name ?? 'User' }}</div>
        </div>
    </div>
</div>

<!-- Alur Aturan Decision Tree -->
<div class="result-card">
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
        <i class="ph ph-tree-structure" style="font-size: 20px; color: var(--blue-accent);"></i>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Alur Aturan Keputusan (Decision Tree Rule Path)</h3>
    </div>
    <div style="background: var(--blue-light); border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--blue-accent); text-transform: uppercase; display: block; margin-bottom: 4px;">Aturan Klasifikasi yang Terpilih:</span>
        <code style="font-family: monospace; font-size: 13px; font-weight: 600; color: var(--navy-dark); display: block; line-height: 1.5;">
            {{ $klasifikasi->rule_text ?: 'Pohon keputusan mengklasifikasikan aset berdasarkan atribut dominan.' }}
        </code>
    </div>

    @if(is_array($klasifikasi->rule_path))
        <div style="margin-top: 12px;">
            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 8px;">Tahapan Percabangan Pohon:</span>
            @foreach($klasifikasi->rule_path as $idx => $step)
                <div class="tree-step">
                    <span style="width: 24px; height: 24px; border-radius: 50%; background: var(--navy-card); color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                        {{ $idx + 1 }}
                    </span>
                    <div style="flex: 1;">
                        <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Node: Atribut {{ strtoupper($step['attribute']) }}</span>
                        <div style="font-size: 13px; font-weight: 700; color: var(--navy-dark);">
                            Cabang Terpilih: <span style="color: var(--blue-accent);">{{ str_replace('_', ' ', $step['value']) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Rekomendasi Aset BMN -->
<div class="result-card">
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
        <i class="ph ph-lightbulb" style="font-size: 20px; color: #EAB308;"></i>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Rekomendasi Tindak Lanjut Aset BMN</h3>
    </div>
    <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 16px;">
        <p style="font-size: 14px; line-height: 1.6; color: #92400E; margin: 0; font-weight: 500;">
            {{ $klasifikasi->rekomendasi }}
        </p>
    </div>
    @if($klasifikasi->catatan)
        <div style="margin-top: 16px; font-size: 12px; color: var(--text-muted);">
            Catatan Tambahan Petugas: <em>"{{ $klasifikasi->catatan }}"</em>
        </div>
    @endif
</div>
@endsection

