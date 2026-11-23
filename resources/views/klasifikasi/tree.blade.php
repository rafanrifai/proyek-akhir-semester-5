@extends('layouts.app')

@section('title', 'Struktur Pohon Keputusan C4.5')

@section('content')
<style>
    .card-box {
        background-color: white;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    th {
        background-color: #F8FAFC;
        padding: 10px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-light);
        text-align: left;
    }
    td {
        padding: 10px 14px;
        border-bottom: 1px solid var(--border-light);
    }
    .badge-label {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }
    .badge-label.BAIK { background: var(--success-bg); color: var(--success-text); }
    .badge-label.RUSAK_RINGAN { background: #FEF3C7; color: #D97706; }
    .badge-label.RUSAK_SEDANG { background: #FFEDD5; color: #EA580C; }
    .badge-label.RUSAK_BERAT { background: var(--danger-bg); color: var(--danger-text); }

    .tree-node-view {
        background: #F8FAFC;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 16px;
        font-family: monospace;
        font-size: 13px;
        line-height: 1.8;
        overflow-x: auto;
    }
</style>

<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
        <a href="{{ route('klasifikasi.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali ke Riwayat Klasifikasi
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--navy-dark); margin: 0;">Struktur Pohon Keputusan (Decision Tree)</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Detail kalkulasi Information Gain dan pohon keputusan algoritma C4.5 pada klasifikasi kerusakan BMN.</p>
    </div>
    <a href="{{ route('klasifikasi.create') }}" style="background-color: var(--navy-card); color: white; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
        <i class="ph ph-plus-circle"></i> Mulai Uji Klasifikasi
    </a>
</div>

<!-- Evaluasi Entropi & Gain Ratio -->
<div class="card-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="ph ph-calculator" style="font-size: 20px; color: var(--blue-accent);"></i>
            <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Hasil Evaluasi Gain Ratio per Atribut</h3>
        </div>
        <div style="background: var(--blue-light); color: var(--blue-accent); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700;">
            Total Entropi Dataset (S): {{ $totalEntropy }}
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nama Atribut (Fitur)</th>
                    <th>Information Gain</th>
                    <th>Split Information</th>
                    <th>Gain Ratio</th>
                    <th>Keterangan Pemisahan (Root / Split)</th>
                </tr>
            </thead>
            <tbody>
                @php $rank = 1; @endphp
                @foreach($attributesGain as $attr => $stat)
                    <tr style="{{ $rank === 1 ? 'background-color: #F0FDF4;' : '' }}">
                        <td>
                            <span style="font-weight: 700; width: 22px; height: 22px; border-radius: 50%; background: {{ $rank === 1 ? 'var(--success-text)' : 'var(--border-light)' }}; color: {{ $rank === 1 ? 'white' : 'var(--text-dark)' }}; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">
                                {{ $rank++ }}
                            </span>
                        </td>
                        <td>
                            <strong style="color: var(--navy-dark);">{{ strtoupper($attr) }}</strong>
                        </td>
                        <td>{{ $stat['gain'] }}</td>
                        <td>{{ $stat['split_info'] }}</td>
                        <td>
                            <strong style="color: {{ $rank === 2 ? 'var(--success-text)' : 'var(--text-dark)' }}; font-size: 14px;">
                                {{ $stat['gain_ratio'] }}
                            </strong>
                        </td>
                        <td>
                            @if($rank === 2)
                                <span style="background: var(--success-bg); color: var(--success-text); font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    Dipilih Sebagai Node Utama (Akar/Root)
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;">Percabangan level turunan</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pohon Keputusan Render -->
<div class="card-box">
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
        <i class="ph ph-tree-structure" style="font-size: 20px; color: var(--blue-accent);"></i>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Representasi Alur Pohon Keputusan C4.5</h3>
    </div>
    
    <div class="tree-node-view">
        <div style="color: var(--blue-accent); font-weight: bold; margin-bottom: 8px;">[ROOT NODE: FISIK]</div>
        @foreach($decisionTree['branches'] ?? [] as $val => $branch)
            <div style="margin-left: 20px;">
                ├── Jika <span style="color: #0369A1; font-weight: bold;">Fisik = {{ $val }}</span>:
                @if(($branch['type'] ?? '') === 'leaf')
                    <span style="color: var(--success-text); font-weight: bold;">&rarr; KEPUTUSAN: [{{ $branch['label'] }}]</span> 
                    <span style="color: var(--text-muted); font-size: 11px;">(Sampel: {{ $branch['count'] ?? 1 }}, Akurasi: {{ round(($branch['confidence'] ?? 1) * 100) }}%)</span>
                @else
                    <div style="margin-left: 30px;">
                        ├── Cabang Node: <strong style="color: #6D28D9;">{{ strtoupper($branch['attribute'] ?? '') }}</strong>
                        @foreach($branch['branches'] ?? [] as $subVal => $subBranch)
                            <div style="margin-left: 20px;">
                                └── Jika {{ $branch['attribute'] }} = <span style="color: #0369A1; font-weight: bold;">{{ $subVal }}</span>:
                                @if(($subBranch['type'] ?? '') === 'leaf')
                                    <span style="color: var(--success-text); font-weight: bold;">&rarr; KEPUTUSAN: [{{ $subBranch['label'] }}]</span>
                                @else
                                    <span style="color: var(--text-muted);">&rarr; Cabang Lanjutan: [{{ strtoupper($subBranch['attribute'] ?? 'NODE') }}]</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Dataset Pelatihan -->
<div class="card-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="ph ph-database" style="font-size: 20px; color: var(--blue-accent);"></i>
            <h3 style="font-size: 16px; font-weight: 800; color: var(--navy-dark); margin: 0;">Dataset Pelatihan Historis (Training Data)</h3>
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Total: {{ count($dataset) }} Data Sampel</span>
    </div>

    <div style="max-height: 380px; overflow-y: auto;">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Usia Barang</th>
                    <th>Intensitas</th>
                    <th>Kondisi Fisik</th>
                    <th>Biaya Perbaikan</th>
                    <th>Riwayat Rusak</th>
                    <th>Label Target (Klasifikasi)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataset as $idx => $row)
                    <tr>
                        <td style="color: var(--text-muted);">{{ $idx + 1 }}</td>
                        <td>{{ $row['usia'] }}</td>
                        <td>{{ $row['intensitas'] }}</td>
                        <td>{{ str_replace('_', ' ', $row['fisik']) }}</td>
                        <td>{{ $row['biaya'] }}</td>
                        <td>{{ str_replace('_', ' ', $row['riwayat']) }}</td>
                        <td>
                            <span class="badge-label {{ $row['label'] }}">{{ str_replace('_', ' ', $row['label']) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

