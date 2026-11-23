<?php

namespace App\Http\Controllers;

use App\Models\Bmn;
use App\Models\RiwayatKondisi;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatKerusakanController extends Controller
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

    public function index(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();

        $query = RiwayatKondisi::with(['bmn.unit', 'user'])
            ->whereHas('bmn', function ($q) use ($allowedUnitIds) {
                $q->whereIn('unit_id', $allowedUnitIds);
            })
            ->latest('id');

        // Filter search nama atau kode barang
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bmn', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi_baru', $request->kondisi);
        }

        $riwayats = $query->paginate(15)->withQueryString();

        // Statistik ringkas
        $totalKerusakan = RiwayatKondisi::whereHas('bmn', function ($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        })->whereIn('kondisi_baru', ['RUSAK_RINGAN', 'RUSAK_SEDANG', 'RUSAK_BERAT'])->count();

        $rusakRingan = RiwayatKondisi::whereHas('bmn', function ($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        })->where('kondisi_baru', 'RUSAK_RINGAN')->count();

        $rusakSedang = RiwayatKondisi::whereHas('bmn', function ($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        })->where('kondisi_baru', 'RUSAK_SEDANG')->count();

        $rusakBerat = RiwayatKondisi::whereHas('bmn', function ($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        })->where('kondisi_baru', 'RUSAK_BERAT')->count();

        return view('riwayat_kerusakan.index', compact(
            'riwayats', 'totalKerusakan', 'rusakRingan', 'rusakSedang', 'rusakBerat'
        ));
    }

    public function create(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        $bmns = Bmn::whereIn('unit_id', $allowedUnitIds)->orderBy('name')->get();
        $selectedBmnId = $request->bmn_id;

        return view('riwayat_kerusakan.create', compact('bmns', 'selectedBmnId'));
    }

    public function store(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();

        $validated = $request->validate([
            'bmn_id' => 'required|exists:bmns,id',
            'kondisi_baru' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_SEDANG,RUSAK_BERAT',
            'catatan' => 'required|string|max:1000',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $bmn = Bmn::findOrFail($validated['bmn_id']);
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak mencatat kerusakan untuk barang di unit ini.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('riwayat_kondisi_fotos', 'public');
        }

        $kondisiLama = $bmn->kondisi;

        RiwayatKondisi::create([
            'bmn_id' => $bmn->id,
            'user_id' => Auth::id(),
            'kondisi_sebelumnya' => $kondisiLama,
            'kondisi_baru' => $validated['kondisi_baru'],
            'catatan' => $validated['catatan'],
            'foto_bukti' => $fotoPath,
        ]);

        // Update kondisi aktif BMN
        $bmn->update([
            'kondisi' => $validated['kondisi_baru'],
            'last_sensus_at' => now(),
        ]);

        return redirect()->route('riwayat-kerusakan.index')->with('success', 'Catatan kerusakan barang berhasil disimpan.');
    }
}

