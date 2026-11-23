@extends('layouts.app')

@section('title', 'Manajemen Pengguna (User)')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Akses & Keamanan</span>
    </div>
    <h2 class="greeting-name">Daftar Pengguna</h2>
    <div class="greeting-role">Kelola Hak Akses Pengguna Aplikasi</div>
</div>

@if(session('success'))
<div style="background-color: var(--success-bg); color: var(--success-text); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background-color: var(--danger-bg); color: var(--danger-text); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; font-weight: 600;">
    {{ $errors->first() }}
</div>
@endif

<div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 16px;">
    <a href="{{ route('users.create') }}" style="background-color: var(--navy-card); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
        <i class="ph ph-user-plus"></i> Tambah Pengguna
    </a>
</div>

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background-color: var(--bg-gray); text-align: left; color: var(--text-muted); font-weight: 600;">
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Nama Pengguna</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Email</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Peran (Role)</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Cabang / Unit</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid var(--border-light);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid var(--border-light);">
                    <td style="padding: 12px 16px; color: var(--text-dark); font-weight: 600;">{{ $user->name }}</td>
                    <td style="padding: 12px 16px; color: var(--text-muted);">{{ $user->email }}</td>
                    <td style="padding: 12px 16px;">
                        @php
                            $roleColors = [
                                'ADMIN' => ['bg' => 'var(--danger-bg)', 'color' => 'var(--danger-text)'],
                                'USER_UTAMA' => ['bg' => '#FEF3C7', 'color' => '#D97706'],
                                'CABANG' => ['bg' => '#E0E7FF', 'color' => '#4338CA']
                            ];
                            $color = $roleColors[$user->role] ?? ['bg' => 'var(--bg-gray)', 'color' => 'var(--text-muted)'];
                        @endphp
                        <span style="background-color: {{ $color['bg'] }}; color: {{ $color['color'] }}; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </td>
                    <td style="padding: 12px 16px; color: var(--text-muted);">{{ $user->unit ? $user->unit->name : '-' }}</td>
                    <td style="padding: 12px 16px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('users.edit', $user->id) }}" style="color: var(--blue-accent); text-decoration: none;"><i class="ph ph-pencil-simple"></i></a>
                            @if(Auth::id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger-text); cursor: pointer; padding: 0;"><i class="ph ph-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="padding: 12px 16px; border-top: 1px solid var(--border-light);">
        {{ $users->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
