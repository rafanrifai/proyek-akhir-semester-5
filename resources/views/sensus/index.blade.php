@extends('layouts.app')

@section('title', 'Sensus Fisik')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Modul Verifikasi</span>
    </div>
    <h2 class="greeting-name">Sensus Fisik (Manual)</h2>
    <div class="greeting-role">Pencarian berdasarkan Satker, Kode Barang, dan NUP</div>
</div>

@if(session('success'))
<div style="background-color: var(--success-bg); color: var(--success-text); padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
    <i class="ph ph-check-circle" style="font-size: 20px;"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background-color: var(--danger-bg); color: var(--danger-text); padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
    <i class="ph ph-warning-circle" style="font-size: 20px;"></i>
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div style="background-color: var(--danger-bg); color: var(--danger-text); padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; font-weight: 600;">
    <ul style="margin: 0; padding-left: 20px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div style="background: white; border: 1px solid var(--border-light); border-radius: 16px; padding: 32px 24px; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <div style="width: 64px; height: 64px; background-color: var(--blue-light); color: var(--blue-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px;">
        <i class="ph ph-magnifying-glass"></i>
    </div>
    <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: var(--text-dark); text-align: center;">Cari Data BMN</h3>
    <p style="margin: 0 auto 24px; max-width: 620px; font-size: 12px; color: var(--text-muted); line-height: 1.5; text-align: center;">Pilih Satker terlebih dahulu, lalu masukkan Kode Barang dan NUP. Kombinasi ini digunakan agar aset yang memiliki kode sama di kantor berbeda tetap dapat disensus satu per satu.</p>

    <form action="{{ route('sensus.search') }}" method="POST" style="max-width: 620px; margin: 0 auto;">
        @csrf

        <div style="margin-bottom: 14px;">
            <label for="unit_id" style="display: block; margin-bottom: 6px; font-size: 12px; color: var(--text-muted); font-weight: 700;">Satker / Unit</label>
            <select id="unit_id" name="unit_id" required style="width: 100%; padding: 12px 16px; border: 2px solid var(--border-light); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; background: white;">
                <option value="">Pilih Satker / Unit...</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ (string) old('unit_id') === (string) $unit->id || (!old('unit_id') && $units->count() === 1) ? 'selected' : '' }}>
                        {{ $unit->code }} - {{ $unit->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
            <div>
                <label for="kode_barang" style="display: block; margin-bottom: 6px; font-size: 12px; color: var(--text-muted); font-weight: 700;">Kode Barang</label>
                <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" required placeholder="Contoh: 3.02.01.01.001" style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 2px solid var(--border-light); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;">
            </div>
            <div>
                <label for="nup" style="display: block; margin-bottom: 6px; font-size: 12px; color: var(--text-muted); font-weight: 700;">NUP</label>
                <input type="text" id="nup" name="nup" value="{{ old('nup') }}" required placeholder="Contoh: 001" style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 2px solid var(--border-light); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;">
            </div>
        </div>

        <button type="submit" style="width: 100%; background-color: var(--navy-card); color: white; border: none; padding: 13px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="ph ph-magnifying-glass"></i> Cari Aset
        </button>
    </form>
</div>
@endsection
