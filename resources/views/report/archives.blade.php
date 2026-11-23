@extends('layouts.app')

@section('title', 'Arsip Laporan - SIM-BMN KEMENAG MINAHASA')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Dokumen Laporan</span>
        <a href="{{ route('report.ba') }}" style="background-color: white; border: 1px solid var(--border-light); padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; color: var(--text-dark); text-decoration: none;">
            <i class="ph ph-plus"></i> Buat Laporan
        </a>
    </div>
    <h2 class="greeting-name">Arsip Laporan</h2>
    <div class="greeting-role">Laporan yang tersimpan tetap sama meskipun data BMN berubah</div>
</div>

@if(session('success'))
    <div style="background-color: var(--success-bg); color: var(--success-text); padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 12px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

<div style="background: white; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden;">
    @forelse($archives as $archive)
        @php
            $archiveTimezone = $archive->timezone ?: 'Asia/Jakarta';
            $timezoneLabel = match ($archiveTimezone) {
                'Asia/Makassar' => 'WITA',
                'Asia/Jayapura' => 'WIT',
                default => 'WIB',
            };
            $savedAt = $archive->created_at?->copy()->setTimezone($archiveTimezone);
        @endphp
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 20px; border-bottom: 1px solid var(--border-light);">
            <div style="min-width: 0;">
                <div style="font-size: 14px; font-weight: 700; color: var(--text-dark);">{{ $archive->title }}</div>
                <div style="margin-top: 5px; font-size: 11px; color: var(--text-muted);">
                    Tanggal laporan: {{ $archive->report_date?->format('d/m/Y') }}
                    &bull; Disimpan: {{ $savedAt?->format('d/m/Y H:i') }} {{ $timezoneLabel }}
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                <a href="{{ route('report.archives.show', $archive) }}" target="_blank" style="background-color: var(--blue-accent); color: white; padding: 9px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none;">
                    <i class="ph ph-file-text"></i> Buka / Cetak PDF
                </a>
                @if(Auth::user()->role === 'ADMIN')
                    <form method="POST" action="{{ route('report.archives.destroy', $archive) }}" onsubmit="return confirm('Hapus arsip laporan ini? Laporan yang dihapus tidak dapat dikembalikan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color: var(--danger-text); color: white; border: 0; padding: 9px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                            <i class="ph ph-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div style="padding: 34px 20px; text-align: center; color: var(--text-muted); font-size: 13px;">
            Belum ada laporan yang disimpan.
        </div>
    @endforelse
</div>
@endsection
