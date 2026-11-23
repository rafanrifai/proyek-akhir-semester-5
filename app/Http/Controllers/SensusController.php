<?php

namespace App\Http\Controllers;

use App\Models\Bmn;
use App\Models\RiwayatKondisi;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensusController extends Controller
{
    /**
     * Get units the current user is allowed to access
     */
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

    public function index()
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $units = Unit::whereIn('id', $allowedUnitIds)
                     ->orderBy('name')
                     ->get();

        return view('sensus.index', compact('units'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|integer|exists:units,id',
            'kode_barang' => 'required|string|max:50',
            'nup' => 'required|string|max:10',
        ]);

        $allowedUnitIds = $this->getAllowedUnitIds();
        $unitId = (int) $validated['unit_id'];

        if (!in_array($unitId, $allowedUnitIds, true)) {
            abort(403, 'Anda tidak berhak mencari BMN pada unit tersebut.');
        }

        // Kode Barang + NUP + Unit adalah identitas pencarian aset.
        $bmns = Bmn::with(['unit', 'kategori', 'fotos'])
                   ->where('unit_id', $unitId)
                   ->where('kode_barang', trim($validated['kode_barang']))
                   ->where('nup', trim($validated['nup']))
                   ->orderBy('id')
                   ->get();

        if ($bmns->isEmpty()) {
            return redirect()->route('sensus.index')->withInput()->with('error', 'Barang tidak ditemukan pada Satker yang dipilih. Periksa kembali Kode Barang dan NUP.');
        }

        // Normalnya hanya satu aset yang cocok. Jika ada duplikat di unit yang sama,
        // tampilkan semuanya agar petugas memilih aset yang benar secara manual.
        if ($bmns->count() > 1) {
            return view('sensus.results', compact('bmns'));
        }

        return view('sensus.show', ['bmn' => $bmns->first()]);
    }

    public function show(Bmn $bmn)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        $bmn->load(['unit', 'kategori', 'fotos']);
        return view('sensus.show', compact('bmn'));
    }

    public function verify(Request $request, Bmn $bmn)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak memverifikasi data ini.');
        }

        $request->validate([
            'kondisi' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_SEDANG,RUSAK_BERAT',
            'catatan' => 'nullable|string|max:500'
        ]);

        $kondisiLama = $bmn->kondisi;
        $bmn->update([
            'kondisi' => $request->kondisi,
            'last_sensus_at' => now(),
        ]);

        RiwayatKondisi::create([
            'bmn_id' => $bmn->id,
            'user_id' => auth()->id(),
            'kondisi_sebelumnya' => $kondisiLama,
            'kondisi_baru' => $request->kondisi,
            'catatan' => $request->catatan ?: 'Verifikasi kondisi fisik melalui sensus lapangan.',
        ]);

        return redirect()->route('sensus.index')->with('success', "Verifikasi berhasil! Aset {$bmn->name} tercatat keberadaannya pada " . now()->format('d-m-Y H:i'));
    }
}
