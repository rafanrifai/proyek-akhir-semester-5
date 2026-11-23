<?php

namespace App\Http\Controllers;

use App\Models\Bmn;
use App\Models\KlasifikasiBmn;
use App\Models\ReportArchive;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function getAllowedUnitIds()
    {
        $user = Auth::user();
        if ($user->role === 'ADMIN') {
            $activeUnitId = session('active_unit_id');
            if ($activeUnitId && Unit::whereKey($activeUnitId)->exists()) {
                return [(int) $activeUnitId];
            }
            return Unit::pluck('id')->toArray();
        } elseif ($user->role === 'USER_UTAMA') {
            $unitIds = [$user->unit_id];
            $children = Unit::where('parent_id', $user->unit_id)->pluck('id')->toArray();
            return array_merge($unitIds, $children);
        } else {
            return [$user->unit_id];
        }
    }

    /**
     * Menu Utama Pusat Laporan (Modul 8)
     */
    public function index()
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $totalBmn = Bmn::whereIn('unit_id', $allowedUnitIds)->count();
        $totalKlasifikasi = KlasifikasiBmn::whereIn('unit_id', $allowedUnitIds)->count();
        $totalArsip = ReportArchive::count();
        $units = Unit::whereIn('id', $allowedUnitIds)->orderBy('name')->get();

        return view('report.index', compact('totalBmn', 'totalKlasifikasi', 'totalArsip', 'units'));
    }

    private function getReportData(?string $timezone = null): array
    {
        $reportTimezone = $timezone ?: config('app.timezone');
        $allowedUnitIds = $this->getAllowedUnitIds();
        $user = Auth::user();

        $activeUnit = $user->role === 'ADMIN' && session('active_unit_id')
            ? Unit::find(session('active_unit_id'))
            : null;
        $unitName = $activeUnit
            ? strtoupper($activeUnit->name)
            : ($user->role === 'ADMIN' ? 'KEMENAG KABUPATEN MINAHASA' : strtoupper($user->unit->name));

        $bmns = Bmn::with(['kategori', 'unit'])
                   ->join('units', 'units.id', '=', 'bmns.unit_id')
                   ->whereIn('bmns.unit_id', $allowedUnitIds)
                   ->select('bmns.*')
                   ->orderBy('bmns.kode_satker')
                   ->orderByRaw('LOWER(bmns.name)')
                   ->orderByRaw('CAST(bmns.nup AS UNSIGNED)')
                   ->orderBy('bmns.nup')
                   ->get();

        $totalNilai = $bmns->sum('harga');
        $totalItem = $bmns->count();

        $baik = $bmns->where('kondisi', 'BAIK')->count();
        $rusakRingan = $bmns->where('kondisi', 'RUSAK_RINGAN')->count();
        $rusakSedang = $bmns->where('kondisi', 'RUSAK_SEDANG')->count();
        $rusakBerat = $bmns->where('kondisi', 'RUSAK_BERAT')->count();

        return compact(
            'bmns',
            'unitName',
            'totalNilai',
            'totalItem',
            'baik',
            'rusakRingan',
            'rusakSedang',
            'rusakBerat',
            'user',
            'allowedUnitIds',
            'reportTimezone'
        );
    }

    public function cetakBA()
    {
        $data = $this->getReportData();
        $data['showArchiveButton'] = true;

        return view('report.ba', $data);
    }

    /**
     * Cetak Laporan Rekapitulasi Data Barang BMN
     */
    public function cetakBmn(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $query = Bmn::with(['unit', 'kategori', 'lokasi'])->whereIn('unit_id', $allowedUnitIds);

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $bmns = $query->orderBy('name')->get();
        $filterKondisi = $request->kondisi ?: 'SEMUA KONDISI';
        $filterUnit = $request->filled('unit_id') ? Unit::find($request->unit_id)?->name : 'Semua Unit';

        return view('report.cetak_bmn', compact('bmns', 'filterKondisi', 'filterUnit'));
    }

    /**
     * Ekspor Data BMN ke format CSV
     */
    public function exportBmnCsv(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $query = Bmn::with(['unit', 'kategori', 'lokasi'])->whereIn('unit_id', $allowedUnitIds);

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $bmns = $query->orderBy('name')->get();

        $filename = 'laporan_bmn_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bmns) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['No', 'Kode Barang', 'NUP', 'Nama Barang', 'Merk/Tipe', 'Tahun', 'Kondisi', 'Harga (Rp)', 'Unit Kerja', 'Lokasi']);

            foreach ($bmns as $idx => $b) {
                fputcsv($file, [
                    $idx + 1,
                    $b->kode_barang,
                    $b->nup,
                    $b->name,
                    $b->merk,
                    $b->tahun_perolehan,
                    str_replace('_', ' ', $b->kondisi),
                    $b->harga,
                    $b->unit->name ?? '-',
                    $b->lokasi->name ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Cetak Laporan Hasil Klasifikasi Machine Learning (Decision Tree)
     */
    public function cetakKlasifikasi(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $query = KlasifikasiBmn::with(['unit', 'user', 'bmn'])->whereIn('unit_id', $allowedUnitIds);

        if ($request->filled('hasil')) {
            $query->where('hasil_klasifikasi', $request->hasil);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $klasifikasis = $query->latest('id')->get();
        $filterHasil = $request->hasil ?: 'SEMUA HASIL PREDIKSI';
        $filterUnit = $request->filled('unit_id') ? Unit::find($request->unit_id)?->name : 'Semua Unit';

        return view('report.cetak_klasifikasi', compact('klasifikasis', 'filterHasil', 'filterUnit'));
    }

    /**
     * Ekspor Hasil Klasifikasi ke format CSV
     */
    public function exportKlasifikasiCsv(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $query = KlasifikasiBmn::with(['unit', 'user', 'bmn'])->whereIn('unit_id', $allowedUnitIds);

        if ($request->filled('hasil')) {
            $query->where('hasil_klasifikasi', $request->hasil);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $klasifikasis = $query->latest('id')->get();

        $filename = 'laporan_klasifikasi_ml_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($klasifikasis) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [
                'No', 'Kode Klasifikasi', 'Tanggal', 'Nama Barang', 'Unit', 
                'Usia (Thn)', 'Kategori Usia', 'Intensitas', 'Kondisi Fisik', 
                'Biaya Perbaikan', 'Riwayat Rusak', 'Hasil Prediksi', 'Confidence (%)', 'Rekomendasi'
            ]);

            foreach ($klasifikasis as $idx => $k) {
                fputcsv($file, [
                    $idx + 1,
                    $k->kode_klasifikasi,
                    $k->created_at->format('Y-m-d H:i'),
                    $k->nama_barang,
                    $k->unit->name ?? '-',
                    $k->usia_barang,
                    $k->kategori_usia,
                    $k->intensitas_pemakaian,
                    str_replace('_', ' ', $k->kondisi_fisik),
                    $k->estimasi_biaya,
                    str_replace('_', ' ', $k->riwayat_kerusakan),
                    str_replace('_', ' ', $k->hasil_klasifikasi),
                    round($k->confidence * 100),
                    $k->rekomendasi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeArchive(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'nullable|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
        ]);

        $timezone = $validated['timezone'] ?? config('app.timezone');
        $data = $this->getReportData($timezone);
        $data['showArchiveButton'] = false;

        $html = view('report.ba', $data)->render();

        ReportArchive::create([
            'title' => 'Laporan BA - ' . $data['unitName'],
            'unit_name' => $data['unitName'],
            'unit_ids' => array_values($data['allowedUnitIds']),
            'report_date' => now($timezone)->toDateString(),
            'timezone' => $timezone,
            'html' => $html,
            'created_by' => $data['user']->id,
        ]);

        return redirect()->route('report.archives')->with('success', 'Laporan berhasil disimpan ke arsip.');
    }

    private function canAccessArchive(ReportArchive $archive): bool
    {
        $allowedUnitIds = array_map('intval', $this->getAllowedUnitIds());
        $archiveUnitIds = array_map('intval', $archive->unit_ids ?? []);

        return !empty($archiveUnitIds) && empty(array_diff($archiveUnitIds, $allowedUnitIds));
    }

    public function archives()
    {
        $archives = ReportArchive::with('creator')
            ->latest()
            ->get()
            ->filter(fn (ReportArchive $archive) => $this->canAccessArchive($archive))
            ->values();

        return view('report.archives', compact('archives'));
    }

    public function showArchive(ReportArchive $archive)
    {
        abort_unless($this->canAccessArchive($archive), 403);

        return response($archive->html);
    }

    public function destroyArchive(ReportArchive $archive)
    {
        abort_unless(Auth::user()->role === 'ADMIN', 403);

        $archive->delete();

        return redirect()->route('report.archives')->with('success', 'Laporan arsip berhasil dihapus.');
    }
}
