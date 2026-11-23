@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Akses & Keamanan</span>
        <a href="{{ route('users.index') }}" style="background-color: white; border: 1px solid var(--border-light); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; color: var(--text-dark); text-decoration: none;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>
    <h2 class="greeting-name">Form Tambah Pengguna</h2>
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
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="email@instansi.go.id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="password">Kata Sandi</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" style="width: 100%; padding: 10px 42px 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
                    <button type="button" data-password-toggle data-target="password" aria-label="Tampilkan kata sandi" style="position: absolute; top: 50%; right: 8px; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; padding: 0; border: 0; background: transparent; color: var(--text-muted); cursor: pointer;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                            <circle cx="12" cy="12" r="2.5"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="role">Hak Akses (Role)</label>
                <select id="role" name="role" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box; background-color: white;">
                    <option value="CABANG" {{ old('role') == 'CABANG' ? 'selected' : '' }}>Cabang / Bawahan</option>
                    <option value="USER_UTAMA" {{ old('role') == 'USER_UTAMA' ? 'selected' : '' }}>Utama / Induk</option>
                    <option value="ADMIN" {{ old('role') == 'ADMIN' ? 'selected' : '' }}>Administrator Nasional</option>
                </select>
            </div>

            <div style="margin-bottom: 24px;" id="unit-group">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;" for="unit_id">Tugaskan ke Unit/Cabang</label>
                <select id="unit_id" name="unit_id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box; background-color: white;">
                    <option value="">-- Tidak Ditugaskan (Admin Saja) --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }} ({{ $unit->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="background-color: var(--blue-accent); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="ph ph-floppy-disk"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            const isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            button.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
            button.setAttribute('aria-pressed', String(!isVisible));
        });
    });
</script>
@endsection
