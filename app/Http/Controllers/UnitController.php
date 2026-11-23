<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('parent')->paginate(10);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $parentUnits = Unit::where('type', 'UTAMA')->get();
        return view('units.create', compact('parentUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:units',
            'type' => 'required|in:UTAMA,CABANG',
            'parent_id' => 'nullable|exists:units,id',
        ]);

        Unit::create($validated);
        return redirect()->route('units.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        $parentUnits = Unit::where('type', 'UTAMA')->where('id', '!=', $unit->id)->get();
        return view('units.edit', compact('unit', 'parentUnits'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:units,code,' . $unit->id,
            'type' => 'required|in:UTAMA,CABANG',
            'parent_id' => 'nullable|exists:units,id',
        ]);

        $unit->update($validated);
        return redirect()->route('units.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus.');
    }
}
