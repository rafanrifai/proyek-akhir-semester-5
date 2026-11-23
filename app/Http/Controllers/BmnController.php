<?php

namespace App\Http\Controllers;

use App\Models\Bmn;
use App\Models\KategoriBmn;
use App\Models\BmnFoto;
use App\Models\RiwayatKondisi;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BmnController extends Controller
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
            return Unit::pluck('id')->toArray(); // Can see all
        } elseif ($user->role === 'USER_UTAMA') {
            // Can see their own unit and its children
            $unitIds = [$user->unit_id];
            $children = Unit::where('parent_id', $user->unit_id)->pluck('id')->toArray();
            return array_merge($unitIds, $children);
        } else {
            // CABANG: Can only see their own unit
            return [$user->unit_id];
        }
    }

    public function index(Request $request)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        
        $query = Bmn::with(['unit', 'kategori', 'fotos'])
                    ->join('units', 'units.id', '=', 'bmns.unit_id')
                    ->whereIn('bmns.unit_id', $allowedUnitIds)
                    ->select('bmns.*')
                    ->orderBy('bmns.kode_satker')
                    ->orderByRaw('LOWER(bmns.name)')
                    ->orderByRaw('CAST(bmns.nup AS UNSIGNED)')
                    ->orderBy('bmns.nup');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bmns.name', 'like', "%{$search}%")
                  ->orWhere('bmns.kode_barang', 'like', "%{$search}%")
                  ->orWhere('bmns.nup', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kondisi')) {
            $query->where('bmns.kondisi', $request->kondisi);
        }
        
        $bmns = $query->paginate(10)->withQueryString();
        return view('bmn.index', compact('bmns'));
    }

    public function create()
    {
        $kategoris = KategoriBmn::all();
        $allowedUnitIds = $this->getAllowedUnitIds();
        $units = Unit::whereIn('id', $allowedUnitIds)->get();
        
        return view('bmn.create', compact('kategoris', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'kode_satker' => 'required|string|max:50',
            'kategori_id' => 'nullable|exists:kategori_bmns,id',
            'kode_barang' => 'required|string|max:50',
            'nup' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'tahun_perolehan' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'kondisi' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_SEDANG,RUSAK_BERAT',
            'kepemilikan' => 'required|in:BMN,PRIBADI',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        // Security check: ensure user is allowed to add to this unit
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($validated['unit_id'], $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak menambahkan BMN ke unit tersebut.');
        }

        // Extract foto from validated
        $foto = $request->file('foto');
        unset($validated['foto']);

        $bmn = Bmn::create($validated);

        if ($foto) {
            $path = $foto->store('bmn_fotos', 'public');
            BmnFoto::create([
                'bmn_id' => $bmn->id,
                'file_path' => $path
            ]);
        }

        // Catat riwayat kondisi awal
        RiwayatKondisi::create([
            'bmn_id' => $bmn->id,
            'user_id' => Auth::id(),
            'kondisi_sebelumnya' => 'BARU',
            'kondisi_baru' => $bmn->kondisi,
            'catatan' => 'Pencatatan awal data inventaris BMN.',
        ]);

        return redirect()->route('bmn.index')->with('success', 'Data BMN berhasil ditambahkan.');
    }

    public function show(Bmn $bmn)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        $bmn->load(['unit', 'kategori', 'lokasi', 'fotos', 'riwayatKondisis.user']);
        return view('bmn.show', compact('bmn'));
    }

    public function edit(Bmn $bmn)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        $kategoris = KategoriBmn::all();
        $units = Unit::whereIn('id', $allowedUnitIds)->get();
        return view('bmn.edit', compact('bmn', 'kategoris', 'units'));
    }

    public function update(Request $request, Bmn $bmn)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak mengubah data ini.');
        }

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'kode_satker' => 'required|string|max:50',
            'kategori_id' => 'nullable|exists:kategori_bmns,id',
            'kode_barang' => 'required|string|max:50',
            'nup' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'tahun_perolehan' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'kondisi' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_SEDANG,RUSAK_BERAT',
            'kepemilikan' => 'required|in:BMN,PRIBADI',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Security check for the new unit_id
        if (!in_array($validated['unit_id'], $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak memindahkan BMN ke unit tersebut.');
        }

        $kondisiLama = $bmn->kondisi;
        $foto = $request->file('foto');
        unset($validated['foto']);

        $bmn->update($validated);

        if ($kondisiLama !== $bmn->kondisi) {
            RiwayatKondisi::create([
                'bmn_id' => $bmn->id,
                'user_id' => Auth::id(),
                'kondisi_sebelumnya' => $kondisiLama,
                'kondisi_baru' => $bmn->kondisi,
                'catatan' => 'Perubahan kondisi melalui formulir pembaharuan BMN.',
            ]);
        }

        if ($foto) {
            // Remove all previous photo records, including stale records whose file is missing.
            $oldFotos = BmnFoto::where('bmn_id', $bmn->id)->get();
            foreach ($oldFotos as $oldFoto) {
                if (Storage::disk('public')->exists($oldFoto->file_path)) {
                    Storage::disk('public')->delete($oldFoto->file_path);
                }
                $oldFoto->delete();
            }

            $path = $foto->store('bmn_fotos', 'public');
            BmnFoto::create([
                'bmn_id' => $bmn->id,
                'file_path' => $path
            ]);
        }

        return redirect()->route('bmn.index')->with('success', 'Data BMN berhasil diperbarui.');
    }

    public function destroy(Bmn $bmn)
    {
        $allowedUnitIds = $this->getAllowedUnitIds();
        if (!in_array($bmn->unit_id, $allowedUnitIds)) {
            abort(403, 'Anda tidak berhak menghapus data ini.');
        }

        // Delete associated photos from storage
        $fotos = BmnFoto::where('bmn_id', $bmn->id)->get();
        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto->file_path)) {
                Storage::disk('public')->delete($foto->file_path);
            }
        }

        $bmn->delete();

        return redirect()->route('bmn.index')->with('success', 'Data BMN berhasil dihapus.');
    }
}
