@extends('layouts.app')

@section('title', 'Verifikasi Sensus')

@section('content')
<div class="greeting-section">
    <div class="greeting-top">
        <span class="greeting-label">Modul Verifikasi</span>
        <a href="{{ route('sensus.index') }}" style="background-color: white; border: 1px solid var(--border-light); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; color: var(--text-dark); text-decoration: none;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>
    <h2 class="greeting-name">Data Ditemukan!</h2>
</div>

<div style="background-color: white; border: 1px solid var(--border-light); border-radius: 16px; overflow: hidden; margin-bottom: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    
    <!-- Header Card -->
    <div style="background: linear-gradient(135deg, var(--navy-card), var(--navy-dark)); padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 10px; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;">
                    <i class="ph ph-buildings"></i> {{ $bmn->kode_satker ?? '-' }} · {{ $bmn->unit->name ?? 'Unit Tidak Diketahui' }}
                </div>
                <h3 style="margin: 0 0 8px; font-size: 20px; font-weight: 800;">{{ $bmn->name }}</h3>
                <div style="display: inline-block; background-color: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    NUP: {{ $bmn->nup }}
                </div>
            </div>
            
            @if($bmn->fotos->first())
                <button type="button" class="photo-preview-trigger" data-photo-modal-open aria-label="Perbesar foto {{ $bmn->name }}">
                    <img src="{{ asset('storage/' . $bmn->fotos->first()->file_path) }}" alt="{{ $bmn->name }}" style="width: 72px; height: 72px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                </button>
            @else
                <div style="width: 72px; height: 72px; background-color: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: rgba(255,255,255,0.5);">
                    <i class="ph ph-image"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Body Card -->
    <div style="padding: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div>
                <span style="display: block; font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Kode Barang</span>
                <span style="font-size: 14px; font-weight: 700; color: var(--text-dark);">{{ $bmn->kode_barang }}</span>
            </div>
            <div>
                <span style="display: block; font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Kategori</span>
                <span style="font-size: 14px; font-weight: 700; color: var(--text-dark);">{{ $bmn->kategori->name ?? '-' }}</span>
            </div>
            <div>
                <span style="display: block; font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Merek / Tipe</span>
                <span style="font-size: 14px; font-weight: 600; color: var(--text-dark);">{{ $bmn->merk ?? '-' }}</span>
            </div>
            <div>
                <span style="display: block; font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Tahun Perolehan</span>
                <span style="font-size: 14px; font-weight: 600; color: var(--text-dark);">{{ $bmn->tahun_perolehan }}</span>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px dashed var(--border-light); margin: 0 0 24px;">

        <!-- Form Verifikasi -->
        <form action="{{ route('sensus.verify', $bmn->id) }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px;">Update Kondisi Fisik (Saat Ini)</label>
                
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                    <label style="cursor: pointer; position: relative;">
                        <input type="radio" name="kondisi" value="BAIK" {{ $bmn->kondisi == 'BAIK' ? 'checked' : '' }} style="position: absolute; opacity: 0; pointer-events: none;" onchange="updateRadioUI(this)">
                        <div class="radio-card {{ $bmn->kondisi == 'BAIK' ? 'active success' : '' }}" style="border: 2px solid var(--border-light); border-radius: 12px; padding: 10px 4px; text-align: center; transition: all 0.2s;">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background-color: var(--success-text); margin: 0 auto 6px;"></div>
                            <span style="font-size: 10px; font-weight: 700;">BAIK</span>
                        </div>
                    </label>

                    <label style="cursor: pointer; position: relative;">
                        <input type="radio" name="kondisi" value="RUSAK_RINGAN" {{ $bmn->kondisi == 'RUSAK_RINGAN' ? 'checked' : '' }} style="position: absolute; opacity: 0; pointer-events: none;" onchange="updateRadioUI(this)">
                        <div class="radio-card {{ $bmn->kondisi == 'RUSAK_RINGAN' ? 'active warning' : '' }}" style="border: 2px solid var(--border-light); border-radius: 12px; padding: 10px 4px; text-align: center; transition: all 0.2s;">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #F59E0B; margin: 0 auto 6px;"></div>
                            <span style="font-size: 10px; font-weight: 700;">R. RINGAN</span>
                        </div>
                    </label>

                    <label style="cursor: pointer; position: relative;">
                        <input type="radio" name="kondisi" value="RUSAK_SEDANG" {{ $bmn->kondisi == 'RUSAK_SEDANG' ? 'checked' : '' }} style="position: absolute; opacity: 0; pointer-events: none;" onchange="updateRadioUI(this)">
                        <div class="radio-card {{ $bmn->kondisi == 'RUSAK_SEDANG' ? 'active orange' : '' }}" style="border: 2px solid var(--border-light); border-radius: 12px; padding: 10px 4px; text-align: center; transition: all 0.2s;">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #EA580C; margin: 0 auto 6px;"></div>
                            <span style="font-size: 10px; font-weight: 700;">R. SEDANG</span>
                        </div>
                    </label>

                    <label style="cursor: pointer; position: relative;">
                        <input type="radio" name="kondisi" value="RUSAK_BERAT" {{ $bmn->kondisi == 'RUSAK_BERAT' ? 'checked' : '' }} style="position: absolute; opacity: 0; pointer-events: none;" onchange="updateRadioUI(this)">
                        <div class="radio-card {{ $bmn->kondisi == 'RUSAK_BERAT' ? 'active danger' : '' }}" style="border: 2px solid var(--border-light); border-radius: 12px; padding: 10px 4px; text-align: center; transition: all 0.2s;">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background-color: var(--danger-text); margin: 0 auto 6px;"></div>
                            <span style="font-size: 10px; font-weight: 700;">R. BERAT</span>
                        </div>
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Catatan Sensus / Riwayat Kerusakan (Opsional)</label>
                <input type="text" name="catatan" placeholder="Contoh: Layar bergaris, baut kendor, dll." style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-light); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; background-color: var(--blue-accent); color: white; border: none; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px;">
                <i class="ph ph-check-square-offset"></i> Verifikasi Sensus
            </button>
        </form>

    </div>
</div>

@if($bmn->fotos->first())
    <div id="photo-modal" class="photo-modal" role="dialog" aria-modal="true" aria-label="Foto {{ $bmn->name }}" data-photo-modal>
        <button type="button" class="photo-modal-close" data-photo-modal-close aria-label="Tutup foto">&times;</button>
        <img src="{{ asset('storage/' . $bmn->fotos->first()->file_path) }}" alt="{{ $bmn->name }}" class="photo-modal-image">
    </div>
@endif

<style>
    /* Styling for custom radio cards */
    .radio-card { color: var(--text-muted); }
    
    .radio-card.active.success { border-color: var(--success-text) !important; background-color: rgba(16, 185, 129, 0.05); color: var(--text-dark); }
    .radio-card.active.warning { border-color: #F59E0B !important; background-color: rgba(245, 158, 11, 0.05); color: var(--text-dark); }
    .radio-card.active.orange { border-color: #EA580C !important; background-color: rgba(234, 88, 12, 0.05); color: var(--text-dark); }
    .radio-card.active.danger { border-color: var(--danger-text) !important; background-color: rgba(239, 68, 68, 0.05); color: var(--text-dark); }

    .photo-preview-trigger {
        padding: 0;
        border: 0;
        border-radius: 12px;
        background: transparent;
        cursor: zoom-in;
        line-height: 0;
    }

    .photo-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(0, 0, 0, 0.88);
        cursor: zoom-out;
    }

    .photo-modal.is-open {
        display: flex;
    }

    .photo-modal-image {
        max-width: min(96vw, 1200px);
        max-height: 90vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
        cursor: default;
    }

    .photo-modal-close {
        position: absolute;
        top: 18px;
        right: 22px;
        width: 42px;
        height: 42px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.16);
        color: white;
        font-size: 30px;
        line-height: 1;
        cursor: pointer;
    }

    .photo-modal-close:hover {
        background: rgba(255, 255, 255, 0.28);
    }
</style>

<script>
    function updateRadioUI(input) {
        // Reset all
        document.querySelectorAll('.radio-card').forEach(card => {
            card.classList.remove('active', 'success', 'warning', 'orange', 'danger');
            card.style.borderColor = 'var(--border-light)';
        });
        
        // Add active to selected
        const label = input.closest('label');
        const card = label.querySelector('.radio-card');
        
        if(input.value === 'BAIK') card.classList.add('active', 'success');
        if(input.value === 'RUSAK_RINGAN') card.classList.add('active', 'warning');
        if(input.value === 'RUSAK_SEDANG') card.classList.add('active', 'orange');
        if(input.value === 'RUSAK_BERAT') card.classList.add('active', 'danger');
    }

    (function () {
        const modal = document.querySelector('[data-photo-modal]');
        const openButton = document.querySelector('[data-photo-modal-open]');
        const closeButton = document.querySelector('[data-photo-modal-close]');

        if (!modal || !openButton || !closeButton) return;

        function closePhotoModal() {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        openButton.addEventListener('click', function () {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            closeButton.focus();
        });

        closeButton.addEventListener('click', closePhotoModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) closePhotoModal();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closePhotoModal();
            }
        });
    })();
</script>

@endsection
