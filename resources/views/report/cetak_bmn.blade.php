<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data BMN - {{ $filterUnit }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }
        .print-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 1.5cm 2cm;
            background-color: white;
            box-sizing: border-box;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .kop-logo {
            width: 90px;
        }
        .kop-text {
            flex: 1;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            padding: 0 10px;
        }
        .kop-text h1 {
            font-size: 14pt;
            margin: 0 0 2px 0;
            font-weight: bold;
        }
        .kop-text h2 {
            font-size: 12pt;
            margin: 0 0 4px 0;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 9pt;
            margin: 0;
        }
        .title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 6px;
        }
        .subtitle {
            text-align: center;
            font-size: 10.5pt;
            margin-bottom: 18px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
        .no-print {
            text-align: center;
            padding: 16px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .btn {
            background-color: #1E3A8A;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none !important; }
            .print-container { padding: 0 !important; width: 100% !important; max-width: none !important; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" class="btn">Cetak Dokumen Sekarang</button>
</div>

<div class="print-container">
    <div class="kop-surat">
        <div class="kop-logo">
            <img src="{{ asset('images/logo_kemenag.png') }}" alt="Logo Kemenag" style="width: 80px; height: auto;">
        </div>
        <div class="kop-text">
            <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
            <h2>KANTOR KEMENTERIAN AGAMA KABUPATEN MINAHASA</h2>
            <p>Jalan Sasaran Tondano, Telp (0431) 321156, Minahasa - Sulawesi Utara</p>
        </div>
        <div class="kop-logo" style="width: 80px;"></div>
    </div>

    <div class="title">LAPORAN REKAPITULASI INVENTARIS BARANG MILIK NEGARA (BMN)</div>
    <div class="subtitle">
        Unit Kerja: <strong>{{ $filterUnit }}</strong> | Kategori Kondisi: <strong>{{ str_replace('_', ' ', $filterKondisi) }}</strong>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Kode Barang</th>
                <th style="width: 45px;">NUP</th>
                <th>Nama Barang / Aset</th>
                <th>Merk / Tipe</th>
                <th style="width: 50px;">Tahun</th>
                <th>Kondisi</th>
                <th>Harga (Rp)</th>
                <th>Unit Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bmns as $idx => $b)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ $b->kode_barang }}</td>
                    <td style="text-align: center;">{{ $b->nup ?? '-' }}</td>
                    <td><strong>{{ $b->name }}</strong></td>
                    <td>{{ $b->merk ?? '-' }}</td>
                    <td style="text-align: center;">{{ $b->tahun_perolehan ?? '-' }}</td>
                    <td style="text-align: center;">{{ str_replace('_', ' ', $b->kondisi) }}</td>
                    <td style="text-align: right;">{{ number_format($b->harga ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $b->unit->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data barang yang sesuai dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9fafb;">
                <td colspan="7" style="text-align: right;">TOTAL ITEM & NILAI:</td>
                <td style="text-align: right;">Rp {{ number_format($bmns->sum('harga'), 0, ',', '.') }}</td>
                <td>{{ $bmns->count() }} Unit Barang</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,<br>Kepala Kantor Kemenag Minahasa</p>
            <div class="signature-name">Pdt. Dolie Tangian, S.Th, M.Pd</div>
            <div>NIP. 197005121998031002</div>
        </div>
        <div class="signature-box">
            <p>Tondano, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Pengelola BMN / Operator</p>
            <div class="signature-name">{{ Auth::user()->name }}</div>
            <div>Petugas Pengelola Inventaris</div>
        </div>
    </div>
</div>

</body>
</html>

