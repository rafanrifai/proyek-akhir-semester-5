<?php

namespace App\Http\Controllers;

use App\Models\Bmn;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
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
        $user = Auth::user();
        $allowedUnitIds = $this->getAllowedUnitIds();

        // Query dasar BMN sesuai hak akses
        $query = Bmn::whereIn('unit_id', $allowedUnitIds);

        // 1. Total Nilai Kapitalisasi
        $totalNilai = $query->sum('harga');
        
        // 2. Total Item
        $totalItem = $query->count();

        // 3. Statistik Kondisi
        $baik = (clone $query)->where('kondisi', 'BAIK')->count();
        $rusakRingan = (clone $query)->where('kondisi', 'RUSAK_RINGAN')->count();
        $rusakBerat = (clone $query)->where('kondisi', 'RUSAK_BERAT')->count();

        $pctBaik = $totalItem > 0 ? round(($baik / $totalItem) * 100, 1) : 0;
        $pctRusakRingan = $totalItem > 0 ? round(($rusakRingan / $totalItem) * 100, 1) : 0;
        $pctRusakBerat = $totalItem > 0 ? round(($rusakBerat / $totalItem) * 100, 1) : 0;

        // 4. Progres Sensus per Unit
        $units = Unit::whereIn('id', $allowedUnitIds)->get();
        $progressSensus = [];
        $currentYear = Carbon::now()->year;

        foreach ($units as $unit) {
            $totalBmnUnit = Bmn::where('unit_id', $unit->id)->count();
            if ($totalBmnUnit > 0) {
                $sensusDone = Bmn::where('unit_id', $unit->id)
                                 ->whereYear('last_sensus_at', $currentYear)
                                 ->count();
                $pctSensus = round(($sensusDone / $totalBmnUnit) * 100);
                
                $progressSensus[] = (object) [
                    'unit_name' => $unit->name,
                    'total_aset' => $totalBmnUnit,
                    'sensus_done' => $sensusDone,
                    'percentage' => $pctSensus
                ];
            }
        }

        // Sort progress sensus by percentage descending
        usort($progressSensus, function($a, $b) {
            return $b->percentage <=> $a->percentage;
        });

        // Current active unit name for display
        $activeUnit = null;
        $activeUnits = collect();
        if ($user->role === 'ADMIN') {
            $activeUnits = Unit::orderBy('name')->get();
            if (session('active_unit_id')) {
                $activeUnit = $activeUnits->firstWhere('id', (int) session('active_unit_id'));
            }
        }
        $activeUnitId = $activeUnit?->id;
        $activeUnitName = $activeUnit?->name ?? ($user->role === 'ADMIN' ? 'Seluruh Wilayah' : $user->unit->name);

        return view('dashboard', compact(
            'totalNilai', 'totalItem', 
            'baik', 'rusakRingan', 'rusakBerat', 
            'pctBaik', 'pctRusakRingan', 'pctRusakBerat',
            'progressSensus', 'activeUnitName', 'activeUnits', 'activeUnitId'
        ));
    }

    public function switchUnit(Request $request)
    {
        abort_unless(Auth::user()->role === 'ADMIN', 403);

        $validated = $request->validate([
            'unit_id' => 'nullable|integer|exists:units,id',
        ]);

        if (empty($validated['unit_id'])) {
            $request->session()->forget('active_unit_id');
            return redirect()->route('dashboard')->with('success', 'Tampilan dikembalikan ke seluruh wilayah.');
        }

        $request->session()->put('active_unit_id', (int) $validated['unit_id']);
        $unit = Unit::findOrFail($validated['unit_id']);

        return redirect()->route('dashboard')->with('success', 'Satuan kerja aktif diubah ke ' . $unit->name . '.');
    }
}
