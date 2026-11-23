<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Sensus BMN</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }
        .print-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 2cm;
            background-color: white;
            box-sizing: border-box;
        }
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .kop-logo {
            width: 110px;
        }
        .kop-spacer {
            width: 110px;
        }
        .kop-text {
            flex: 1;
            text-align: center;
            margin: 0;
            padding: 0 20px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .kop-text h1 {
            font-size: 16pt;
            margin: 0 0 2px 0;
            font-weight: bold;
            white-space: nowrap;
        }
        .kop-text h2 {
            font-size: 14pt;
            margin: 0 0 5px 0;
            font-weight: bold;
            white-space: nowrap;
        }
        .kop-text p {
            font-size: 10pt;
            margin: 0;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 200px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-name {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        /* Non-Printable Elements */
        .no-print {
            text-align: center;
            padding: 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .print-btn {
            background-color: #0f172a;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .print-btn:hover {
            opacity: 0.9;
        }
        .back-btn {
            background-color: white;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px;
        }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .print-container { padding: 0; box-shadow: none; max-width: none; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('dashboard') }}" class="back-btn">Kembali ke Dasbor</a>
        <a href="{{ route('report.archives') }}" class="back-btn">Arsip Laporan</a>
        @if($showArchiveButton ?? true)
            <form method="POST" action="{{ route('report.archives.store') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="timezone" id="report-timezone" value="{{ $reportTimezone ?? config('app.timezone') }}">
                <button type="submit" class="print-btn" style="background-color: #0f766e;">
                    Simpan ke Arsip
                </button>
            </form>
        @endif
        <button onclick="window.print()" class="print-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,96V200a16,16,0,0,1-16,16H168v24a8,8,0,0,1-8,8H96a8,8,0,0,1-8-8V216H48a16,16,0,0,1-16-16V96A16,16,0,0,1,48,80H64V40a8,8,0,0,1,8-8h112a8,8,0,0,1,8,8V80h16A16,16,0,0,1,224,96ZM80,48V80H176V48Zm88,176V184H88v40Zm40-128H48v96H80V168a8,8,0,0,1,8-8h80a8,8,0,0,1,8,8v24h32ZM184,120a8,8,0,1,0-8-8A8,8,0,0,0,184,120Z"></path></svg>
            Cetak Laporan / Simpan PDF
        </button>
        <p style="font-size: 12px; color: #64748b; margin-top: 10px;">Atur margin printer ke "Default" atau "None" untuk hasil terbaik.</p>
    </div>

    <script>
        (function () {
            const timezoneInput = document.getElementById('report-timezone');
            if (!timezoneInput) return;

            const deviceTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const timezoneMap = {
                'Asia/Jakarta': 'Asia/Jakarta',
                'Asia/Pontianak': 'Asia/Jakarta',
                'Asia/Makassar': 'Asia/Makassar',
                'Asia/Ujung_Pandang': 'Asia/Makassar',
                'Asia/Jayapura': 'Asia/Jayapura'
            };

            timezoneInput.value = timezoneMap[deviceTimezone] || 'Asia/Jakarta';
        })();
    </script>

    <div class="print-container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo_kemenag.png') }}" alt="Logo Kemenag" class="kop-logo">
            <div class="kop-text">
                <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
                <h2>KANTOR KEMENTERIAN AGAMA KABUPATEN MINAHASA</h2>
                <p>Jalan Manguni Nomor 11 Kelurahan Wewelen Kecamatan Tondano Barat<br>
                Telpon (0431) 321200; Faksimili (0431) 321200<br>
                e-mail : <a href="mailto:kabminahasa@kemenag.go.id" style="color: blue; text-decoration: underline;">kabminahasa@kemenag.go.id</a></p>
            </div>
            <div class="kop-spacer"></div>
        </div>

        <div class="title">LAPORAN HASIL INVENTARISASI DAN SENSUS FISIK<br>BARANG MILIK NEGARA (BMN)</div>

        <!-- Info Section -->
        <div class="info-section">
            <p>Pada hari ini, tanggal <strong>{{ now($reportTimezone ?? config('app.timezone'))->isoFormat('D MMMM YYYY') }}</strong>, telah dilakukan inventarisasi dan sensus fisik Barang Milik Negara (BMN) pada lingkungan kerja {{ $unitName }}, dengan hasil ringkasan sebagai berikut:</p>
            
            <table class="info-table">
                <tr>
                    <td>Kantor / Unit</td>
                    <td>: <strong>{{ $unitName }}</strong></td>
                </tr>
                <tr>
                    <td>Total Aset Terdaftar</td>
                    <td>: <strong>{{ number_format($totalItem, 0, ',', '.') }}</strong> Unit</td>
                </tr>
                <tr>
                    <td>Total Nilai Kapitalisasi</td>
                    <td>: <strong>Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Kondisi Baik</td>
                    <td>: {{ number_format($baik, 0, ',', '.') }} Unit</td>
                </tr>
                <tr>
                    <td>Kondisi Rusak Ringan</td>
                    <td>: {{ number_format($rusakRingan, 0, ',', '.') }} Unit</td>
                </tr>
                <tr>
                    <td>Kondisi Rusak Berat</td>
                    <td>: {{ number_format($rusakBerat, 0, ',', '.') }} Unit</td>
                </tr>
            </table>
        </div>

        <!-- Data Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Kode Satker</th>
                    <th width="14%">Kode Barang / NUP</th>
                    <th width="20%">Nama Barang / Merek</th>
                    <th width="12%">Nilai Aset</th>
                    <th width="8%">Tahun</th>
                    <th width="10%">Kondisi</th>
                    <th width="10%">Status Sensus</th>
                    <th width="9%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bmns as $index => $bmn)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $bmn->kode_satker ?? '-' }}</td>
                        <td>
                            {{ $bmn->kode_barang }}<br>
                            <small>NUP: {{ $bmn->nup }}</small>
                        </td>
                        <td>
                            <strong>{{ $bmn->name }}</strong><br>
                            <small>{{ $bmn->merk ?? '-' }}</small>
                        </td>
                        <td align="right">
                            @if($bmn->harga !== null)
                                Rp {{ number_format($bmn->harga, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td align="center">{{ $bmn->tahun_perolehan }}</td>
                        <td align="center">
                            @if($bmn->kondisi == 'BAIK')
                                Baik
                            @elseif($bmn->kondisi == 'RUSAK_RINGAN')
                                R. Ringan
                            @else
                                R. Berat
                            @endif
                        </td>
                        <td align="center">
                            @if($bmn->last_sensus_at)
                                Selesai<br>
                                <span style="font-size: 8pt;">{{ \Carbon\Carbon::parse($bmn->last_sensus_at)->format('d/m/Y') }}</span>
                            @else
                                Belum
                            @endif
                        </td>
                        <td align="center">
                            @if($bmn->kepemilikan == 'BMN')
                                Aset BMN
                            @else
                                Barang Pribadi
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" align="center">Tidak ada data BMN untuk unit ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p>Demikian Laporan Hasil Inventarisasi ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,<br>Kepala</p>
                <div class="signature-name">(Pdt. Dolie Tangian, S.Th, M.Pd)</div>
                <p>NIP. 197107112003122002</p>
            </div>
            <div class="signature-box">
                <p>Dibuat oleh,<br>Pengelola BMN</p>
                <div class="signature-name">(Santi Daud, S.Tr. Kes)</div>
                <p>NIP. 197511281999032006</p>
            </div>
        </div>
    </div>

</body>
</html>
