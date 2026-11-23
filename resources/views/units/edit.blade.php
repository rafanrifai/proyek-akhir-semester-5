@extends('layouts.app')

@section('title', 'Edit Cabang / Unit')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Manajemen Organisasi</span>
        <a href="{{ route('units.index') }}" style="background-color: white; border: 1px solid var(--border-light); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; color: var(--text-dark); text-decoration: none;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>
    <h2 class="greeting-name">Form Edit Unit Kerja</h2>
</div>

@if($errors->any())
<div style="background-color: var(--danger-bg); color: var(--danger-text); padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; font-weight: 600;">
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; margin-bottom: 30px; max-width: 600px;">
    <div style="padding: 24px;">
        <form action="{{ route('units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="code">Kode Unit / Satker</label>
                <input type="text" id="code" name="code" value="{{ old('code', $unit->code) }}" required placeholder="Contoh: 123456 (Kode Satker)" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="name">Nama Unit / Cabang</label>
                <input type="text" id="name" name="name" value="{{ old('name', $unit->name) }}" required placeholder="Contoh: Cabang Jakarta Pusat" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="type">Tipe Unit</label>
                <select id="type" name="type" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box; background-color: white;">
                    <option value="CABANG" {{ old('type', $unit->type) == 'CABANG' ? 'selected' : '' }}>Cabang (Bawahan)</option>
                    <option value="UTAMA" {{ old('type', $unit->type) == 'UTAMA' ? 'selected' : '' }}>Utama (Induk)</option>
                </select>
            </div>

            <div style="margin-bottom: 24px;" id="parent-group">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="parent_id">Induk Unit (Opsional)</label>
                <select id="parent_id" name="parent_id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box; background-color: white;">
                    <option value="">-- Tidak Ada Induk --</option>
                    @foreach($parentUnits as $parent)
                        @if($parent->id != $unit->id) <!-- Prevent self-parenting -->
                            <option value="{{ $parent->id }}" {{ old('parent_id', $unit->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }} ({{ $parent->code }})
                            </option>
                        @endif
                    @endforeach
                </select>
                <small style="color: var(--text-muted); font-size: 10px; margin-top: 6px; display: block;">Jika ini adalah cabang, pilih unit utama sebagai induknya.</small>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="background-color: var(--blue-accent); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="ph ph-floppy-disk"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
