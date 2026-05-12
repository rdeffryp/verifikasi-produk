@extends('layouts.app')

@section('title', 'Kelola Template Label')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-layout-text-window"></i> Kelola Template Label</span>
            </div>
            <div class="card-body">

                {{-- Form Upload Template --}}
                <div class="card mb-4" style="border: 1px dashed var(--beige-border) !important; background: var(--cream) !important;">
                    <div class="card-body">
                        <h6 class="mb-3" style="color: var(--brown);"><i class="bi bi-upload me-2"></i>Upload Template Baru</h6>

                        <form action="{{ route('label-templates.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Template <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_template" class="form-control" placeholder="Contoh: Roti Sobek Cokelat" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">File Template PNG <span class="text-danger">*</span></label>
                                    <input type="file" name="file_template" class="form-control" accept=".png" id="fileInput" required>
                                    <small class="text-muted">Format PNG, maks 10MB, resolusi 300 DPI dari Canva</small>
                                </div>
                            </div>

                            {{-- Preview & Drag QR --}}
                            <div id="previewSection" class="mt-3 d-none">
                                <label class="form-label fw-500" style="color: var(--brown);">
                                    <i class="bi bi-arrows-move me-1"></i>
                                    <strong>Geser</strong> kotak QR ke posisi yang tepat. Resize dari <strong>sudut kanan bawah</strong>.
                                </label>
                                <div style="position: relative; display: inline-block; border: 2px solid var(--beige-border); border-radius: 8px; overflow: hidden; max-width: 100%;">
                                    <img id="previewImg" src="" alt="Preview Label" style="max-width: 100%; display: block;">
                                    <canvas id="overlayCanvas" style="position: absolute; top: 0; left: 0;"></canvas>
                                </div>
                                <div class="mt-2 d-flex gap-2 align-items-center flex-wrap">
                                    <span id="clickStatus" class="badge" style="background: var(--cream-dark) !important; color: var(--brown) !important; font-size: 13px !important; padding: 6px 12px !important;">
                                        Upload gambar dulu
                                    </span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="resetClick">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Posisi
                                    </button>
                                </div>
                            </div>

                            {{-- Hidden inputs hasil drag --}}
                            <input type="hidden" name="qr_size_px" id="qr_size_px">
                            <input type="hidden" name="pos_x" id="pos_x">
                            <input type="hidden" name="pos_y" id="pos_y">

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="bi bi-save me-1"></i> Simpan Template
                                </button>
                                <small class="text-muted ms-2" id="submitHint">Upload gambar dulu untuk menentukan posisi QR</small>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Daftar Template --}}
                @if($templates->isEmpty())
                    <div class="text-center py-4" style="color: var(--text-muted);">
                        <i class="bi bi-layout-text-window" style="font-size: 40px; opacity: 0.3;"></i>
                        <p class="mt-2">Belum ada template. Upload template pertamamu!</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($templates as $template)
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div style="background: var(--cream); border-radius: 12px 12px 0 0; overflow: hidden; height: 180px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset($template->file_path) }}" alt="{{ $template->nama_template }}"
                                         style="max-width: 100%; max-height: 180px; object-fit: contain;">
                                </div>
                                <div class="card-body">
                                    <h6 class="mb-1" style="color: var(--brown-dark);">{{ $template->nama_template }}</h6>
                                    <small class="text-muted">
                                        QR: {{ $template->qr_size_px }}px &nbsp;|&nbsp;
                                        X: {{ $template->pos_x }}px &nbsp;|&nbsp;
                                        Y: {{ $template->pos_y }}px
                                    </small>
                                </div>
                                <div class="card-footer" style="background: transparent; border-top: 1px solid var(--beige-border);">
                                    <form action="{{ route('label-templates.destroy', $template->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus template {{ $template->nama_template }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="color: #DC2626; border: 1px solid #FCA5A5; background: transparent; border-radius: 8px;">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const fileInput      = document.getElementById('fileInput');
const previewImg     = document.getElementById('previewImg');
const previewSection = document.getElementById('previewSection');
const overlayCanvas  = document.getElementById('overlayCanvas');
const clickStatus    = document.getElementById('clickStatus');
const submitBtn      = document.getElementById('submitBtn');
const submitHint     = document.getElementById('submitHint');

let imgNaturalW = 0, imgNaturalH = 0;
let qr = { x: 50, y: 50, size: 100 };
let dragging = false, resizing = false;
let dragOffX = 0, dragOffY = 0;

fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewSection.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

previewImg.addEventListener('load', function () {
    imgNaturalW = previewImg.naturalWidth;
    imgNaturalH = previewImg.naturalHeight;
    qr.size = Math.round(previewImg.offsetWidth * 0.15);
    qr.x = Math.round((previewImg.offsetWidth - qr.size) / 2);
    qr.y = Math.round((previewImg.offsetHeight - qr.size) / 2);
    resizeCanvas();
    drawQr();
    updateHiddenInputs();
    submitBtn.disabled = false;
    submitHint.textContent = 'Geser QR ke posisi yang tepat, resize dari sudut kanan bawah';
});

function resizeCanvas() {
    overlayCanvas.width  = previewImg.offsetWidth;
    overlayCanvas.height = previewImg.offsetHeight;
}

function drawQr() {
    const ctx = overlayCanvas.getContext('2d');
    ctx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

    ctx.shadowColor = 'rgba(0,0,0,0.3)';
    ctx.shadowBlur = 8;
    ctx.fillStyle = 'rgba(255,255,255,0.92)';
    ctx.fillRect(qr.x, qr.y, qr.size, qr.size);
    ctx.shadowBlur = 0;

    ctx.strokeStyle = '#6B3E26';
    ctx.lineWidth = 2;
    ctx.setLineDash([]);
    ctx.strokeRect(qr.x, qr.y, qr.size, qr.size);

    const cell = Math.round(qr.size / 7);
    const pattern = [
        [1,1,1,1,1,1,1],
        [1,0,0,0,0,0,1],
        [1,0,1,1,1,0,1],
        [1,0,1,0,1,0,1],
        [1,0,1,1,1,0,1],
        [1,0,0,0,0,0,1],
        [1,1,1,1,1,1,1],
    ];
    ctx.fillStyle = '#3D1A0A';
    for (let r = 0; r < 7; r++) {
        for (let c = 0; c < 7; c++) {
            if (pattern[r][c]) {
                ctx.fillRect(qr.x + c * cell + 2, qr.y + r * cell + 2, cell - 1, cell - 1);
            }
        }
    }

    const hw = 16;
    ctx.fillStyle = '#6B3E26';
    ctx.fillRect(qr.x + qr.size - hw, qr.y + qr.size - hw, hw, hw);
    ctx.fillStyle = '#fff';
    ctx.font = '11px sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('↔', qr.x + qr.size - hw/2, qr.y + qr.size - hw/2);
}

function updateHiddenInputs() {
    const scaleX = imgNaturalW / previewImg.offsetWidth;
    const scaleY = imgNaturalH / previewImg.offsetHeight;
    const realX    = Math.round(qr.x * scaleX);
    const realY    = Math.round(qr.y * scaleY);
    const realSize = Math.round(qr.size * ((scaleX + scaleY) / 2));

    document.getElementById('pos_x').value      = realX;
    document.getElementById('pos_y').value      = realY;
    document.getElementById('qr_size_px').value = realSize;

    clickStatus.textContent = `X: ${realX}px | Y: ${realY}px | Size: ${realSize}px`;
}

overlayCanvas.addEventListener('mousedown', e => {
    const rect = overlayCanvas.getBoundingClientRect();
    const mx = e.clientX - rect.left;
    const my = e.clientY - rect.top;
    const hw = 16;

    if (mx >= qr.x + qr.size - hw && mx <= qr.x + qr.size &&
        my >= qr.y + qr.size - hw && my <= qr.y + qr.size) {
        resizing = true;
    } else if (mx >= qr.x && mx <= qr.x + qr.size &&
               my >= qr.y && my <= qr.y + qr.size) {
        dragging = true;
        dragOffX = mx - qr.x;
        dragOffY = my - qr.y;
    }
});

overlayCanvas.addEventListener('mousemove', e => {
    const rect = overlayCanvas.getBoundingClientRect();
    const mx = e.clientX - rect.left;
    const my = e.clientY - rect.top;
    const hw = 16;

    if (dragging) {
        qr.x = Math.max(0, Math.min(mx - dragOffX, overlayCanvas.width - qr.size));
        qr.y = Math.max(0, Math.min(my - dragOffY, overlayCanvas.height - qr.size));
        drawQr();
        updateHiddenInputs();
    } else if (resizing) {
        const newSize = Math.max(30, Math.min(mx - qr.x, overlayCanvas.width - qr.x, my - qr.y, overlayCanvas.height - qr.y));
        qr.size = newSize;
        drawQr();
        updateHiddenInputs();
    }

    if (mx >= qr.x + qr.size - hw && mx <= qr.x + qr.size &&
        my >= qr.y + qr.size - hw && my <= qr.y + qr.size) {
        overlayCanvas.style.cursor = 'nwse-resize';
    } else if (mx >= qr.x && mx <= qr.x + qr.size && my >= qr.y && my <= qr.y + qr.size) {
        overlayCanvas.style.cursor = 'grab';
    } else {
        overlayCanvas.style.cursor = 'default';
    }
});

overlayCanvas.addEventListener('mouseup', () => { dragging = false; resizing = false; });
overlayCanvas.addEventListener('mouseleave', () => { dragging = false; resizing = false; });

overlayCanvas.addEventListener('touchstart', e => {
    e.preventDefault();
    const t = e.touches[0];
    const rect = overlayCanvas.getBoundingClientRect();
    const mx = t.clientX - rect.left;
    const my = t.clientY - rect.top;
    const hw = 20;

    if (mx >= qr.x + qr.size - hw && mx <= qr.x + qr.size &&
        my >= qr.y + qr.size - hw && my <= qr.y + qr.size) {
        resizing = true;
    } else if (mx >= qr.x && mx <= qr.x + qr.size && my >= qr.y && my <= qr.y + qr.size) {
        dragging = true;
        dragOffX = mx - qr.x;
        dragOffY = my - qr.y;
    }
}, { passive: false });

overlayCanvas.addEventListener('touchmove', e => {
    e.preventDefault();
    const t = e.touches[0];
    const rect = overlayCanvas.getBoundingClientRect();
    const mx = t.clientX - rect.left;
    const my = t.clientY - rect.top;

    if (dragging) {
        qr.x = Math.max(0, Math.min(mx - dragOffX, overlayCanvas.width - qr.size));
        qr.y = Math.max(0, Math.min(my - dragOffY, overlayCanvas.height - qr.size));
        drawQr();
        updateHiddenInputs();
    } else if (resizing) {
        const newSize = Math.max(30, Math.min(mx - qr.x, overlayCanvas.width - qr.x));
        qr.size = newSize;
        drawQr();
        updateHiddenInputs();
    }
}, { passive: false });

overlayCanvas.addEventListener('touchend', () => { dragging = false; resizing = false; });

document.getElementById('resetClick').addEventListener('click', () => {
    if (imgNaturalW > 0) {
        qr.size = Math.round(previewImg.offsetWidth * 0.15);
        qr.x = Math.round((previewImg.offsetWidth - qr.size) / 2);
        qr.y = Math.round((previewImg.offsetHeight - qr.size) / 2);
        drawQr();
        updateHiddenInputs();
    }
});
</script>
@endpush