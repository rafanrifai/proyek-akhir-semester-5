<?php

namespace App\Http\Controllers;

use App\Models\Bmn;
use App\Models\KlasifikasiBmn;
use App\Models\RiwayatKondisi;
use App\Models\Unit;
use App\Services\DecisionTreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KlasifikasiController extends Controller
{
    protected DecisionTreeService $dtService;

    public function __construct(DecisionTreeService $dtService)
    {
        $this->dtService = $dtService;
    }

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
     * Modul 7: Hasil dan Riwayat Klasifikasi
     */
    public function index(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();

        $query = KlasifikasiBmn::with(['bmn', 'unit', 'user'])
            ->whereIn('unit_id', $allowedUnitIds)
            ->latest('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_klasifikasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('hasil')) {
            $query->where('hasil_klasifikasi', $request->hasil);
        }

        $riwayats = $query->paginate(15)->withQueryString();

        // Statistik
        $totalPrediksi = KlasifikasiBmn::whereIn('unit_id', $allowedUnitIds)->count();
        $prediksiBaik = KlasifikasiBmn::whereIn('unit_id', $allowedUnitIds)->where('hasil_klasifikasi', 'BAIK')->count();
        $prediksiRingan = KlasifikasiBmn::whereIn('unit_id', $allowedUnitIds)->where('hasil_klasifikasi', 'RUSAK_RINGAN')->count();
        $prediksiSedang = KlasifikasiBmn::whereIn('unit_id', $allowedUnitIds)->where('hasil_klasifikasi', 'RUSAK_SEDANG')->count();
        $prediksiBerat = KlasifikasiBmn::whereIn('unit_id', $allowedUnitIds)->where('hasil_klasifikasi', 'RUSAK_BERAT')->count();

        return view('klasifikasi.index', compact(
            'riwayats', 'totalPrediksi', 'prediksiBaik', 'prediksiRingan', 'prediksiSedang', 'prediksiBerat'
        ));
    }

    /**
     * Modul 6: Form Masukan Klasifikasi Machine Learning
     */
    public function create(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $bmns = Bmn::whereIn('unit_id', $allowedUnitIds)->with(['riwayatKondisis'])->orderBy('name')->get();

        $selectedBmn = null;
        $suggestedUsia = null;
        $suggestedRiwayat = null;

        if ($request->filled('bmn_id')) {
            $selectedBmn = Bmn::whereIn('unit_id', $allowedUnitIds)->find($request->bmn_id);
            if ($selectedBmn && $selectedBmn->tahun_perolehan) {
                $suggestedUsia = max(0, date('Y') - $selectedBmn->tahun_perolehan);
                $damageCount = $selectedBmn->riwayatKondisis->whereIn('kondisi_baru', ['RUSAK_RINGAN', 'RUSAK_SEDANG', 'RUSAK_BERAT'])->count();
                if ($damageCount === 0) {
                    $suggestedRiwayat = 'TIDAK_ADA';
                } elseif ($damageCount <= 2) {
                    $suggestedRiwayat = '1_2_KALI';
                } else {
                    $suggestedRiwayat = 'LEBIH_2_KALI';
                }
            }
        }

        return view('klasifikasi.create', compact('bmns', 'selectedBmn', 'suggestedUsia', 'suggestedRiwayat'));
    }

    /**
     * Eksekusi Prediksi Algoritma Decision Tree & Simpan Riwayat
     */
    public function store(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();

        $validated = $request->validate([
            'bmn_id' => 'nullable|exists:bmns,id',
            'nama_barang' => 'required|string|max:255',
            'usia_barang' => 'required|integer|min:0|max:100',
            'intensitas_pemakaian' => 'required|in:RENDAH,SEDANG,TINGGI',
            'kondisi_fisik' => 'required|in:BAIK,CACAT_RINGAN,RUSAK_SEBAGIAN,RUSAK_TOTAL',
            'estimasi_biaya' => 'required|in:KECIL,SEDANG,BESAR',
            'riwayat_kerusakan' => 'required|in:TIDAK_ADA,1_2_KALI,LEBIH_2_KALI',
            'update_kondisi_bmn' => 'nullable|boolean',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Tentukan Unit ID
        $bmn = null;
        if (!empty($validated['bmn_id'])) {
            $bmn = Bmn::findOrFail($validated['bmn_id']);
            if (!in_array($bmn->unit_id, $allowedUnitIds)) {
                abort(403, 'Anda tidak berhak mengklasifikasi BMN dari unit tersebut.');
            }
            $unitId = $bmn->unit_id;
        } else {
            $unitId = Auth::user()->unit_id ?: $allowedUnitIds[0];
        }

        // Kategorisasi Usia Barang untuk Decision Tree
        $usia = (int) $validated['usia_barang'];
        if ($usia <= 3) {
            $kategoriUsia = 'BARU';
        } elseif ($usia <= 6) {
            $kategoriUsia = 'SEDANG';
        } else {
            $kategoriUsia = 'TUA';
        }

        // Siapkan input fitur untuk Decision Tree
        $inputFeatures = [
            'usia' => $kategoriUsia,
            'intensitas' => $validated['intensitas_pemakaian'],
            'fisik' => $validated['kondisi_fisik'],
            'biaya' => $validated['estimasi_biaya'],
            'riwayat' => $validated['riwayat_kerusakan'],
        ];

        // Eksekusi Prediksi Machine Learning Decision Tree
        $prediksi = $this->dtService->predict($inputFeatures);

        // Generate Kode Klasifikasi unik
        $kodeKlasifikasi = 'DT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $klasifikasi = KlasifikasiBmn::create([
            'kode_klasifikasi' => $kodeKlasifikasi,
            'bmn_id' => $bmn?->id,
            'user_id' => Auth::id(),
            'unit_id' => $unitId,
            'nama_barang' => $validated['nama_barang'],
            'usia_barang' => $usia,
            'kategori_usia' => $kategoriUsia,
            'intensitas_pemakaian' => $validated['intensitas_pemakaian'],
            'kondisi_fisik' => $validated['kondisi_fisik'],
            'estimasi_biaya' => $validated['estimasi_biaya'],
            'riwayat_kerusakan' => $validated['riwayat_kerusakan'],
            'hasil_klasifikasi' => $prediksi['hasil_klasifikasi'],
            'confidence' => $prediksi['confidence'],
            'rule_path' => $prediksi['rule_path'],
            'rule_text' => $prediksi['rule_text'],
            'rekomendasi' => $prediksi['rekomendasi'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Opsional: Perbarui kondisi pada master data BMN jika dicentang
        if (!empty($validated['update_kondisi_bmn']) && $bmn) {
            $kondisiLama = $bmn->kondisi;
            $bmn->update([
                'kondisi' => $prediksi['hasil_klasifikasi'],
                'last_sensus_at' => now(),
            ]);

            RiwayatKondisi::create([
                'bmn_id' => $bmn->id,
                'user_id' => Auth::id(),
                'kondisi_sebelumnya' => $kondisiLama,
                'kondisi_baru' => $prediksi['hasil_klasifikasi'],
                'catatan' => "Perubahan kondisi otomatis berdasarkan hasil Klasifikasi Decision Tree ({$kodeKlasifikasi}).",
            ]);
        }

        return redirect()->route('klasifikasi.show', $klasifikasi->id)
            ->with('success', 'Klasifikasi Decision Tree berhasil diproses!');
    }

    /**
     * Tampilan Detail Hasil Klasifikasi
     */
    public function show(KlasifikasiBmn $klasifikasi)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($klasifikasi->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak melihat hasil klasifikasi ini.');
        }

        $klasifikasi->load(['bmn.unit', 'unit', 'user']);

        return view('klasifikasi.show', compact('klasifikasi'));
    }

    /**
     * Visualisasi Pohon Keputusan & Perhitungan Entropi / Information Gain
     */
    public function tree()
    {
        $dataset = $this->dtService->getTrainingData();
        $totalEntropy = $this->dtService->calculateEntropy($dataset);
        $attributesGain = $this->dtService->evaluateAllAttributes($dataset, [
            'fisik', 'biaya', 'usia', 'intensitas', 'riwayat'
        ]);
        $decisionTree = $this->dtService->buildTree();

        return view('klasifikasi.tree', compact('dataset', 'totalEntropy', 'attributesGain', 'decisionTree'));
    }

    public function destroy(KlasifikasiBmn $klasifikasi)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($klasifikasi->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak menghapus data ini.');
        }

        $klasifikasi->delete();

        return redirect()->route('klasifikasi.index')->with('success', 'Riwayat klasifikasi berhasil dihapus.');
    }
}

