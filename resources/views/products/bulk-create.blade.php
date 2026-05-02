@extends('layouts.app')

@section('title', 'Bulk Generate Produk')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Bulk Generate Produk</h5>
            </div>
            <div class="card-body">
               

                <form action="{{ route('products.bulk-store') }}" method="POST" id="bulkForm">
                    @csrf
                    
                    <!-- Nama Produk -->
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_produk') is-invalid @enderror" 
                               id="nama_produk" 
                               name="nama_produk" 
                               value="{{ old('nama_produk') }}"
                               placeholder="Contoh: Roti Sobek Cokelat"
                               required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nama ini akan dipakai untuk semua produk yang di-generate</small>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">
                            Jumlah Produk <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('quantity') is-invalid @enderror" 
                               id="quantity" 
                               name="quantity" 
                               value="{{ old('quantity', 10) }}"
                               min="1"
                               max="100"
                               required
                               onchange="updatePreview()">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 1, Maksimal 100 produk</small>
                    </div>

                    <!-- Tanggal Produksi -->
                    <div class="mb-3">
                        <label for="tanggal_produksi" class="form-label">
                            Tanggal Produksi <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('tanggal_produksi') is-invalid @enderror" 
                               id="tanggal_produksi" 
                               name="tanggal_produksi" 
                               value="{{ old('tanggal_produksi', date('Y-m-d')) }}"
                               required>
                        @error('tanggal_produksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Kadaluarsa -->
                    <div class="mb-3">
                        <label for="tanggal_kadaluarsa" class="form-label">
                            Tanggal Kadaluarsa <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" 
                               id="tanggal_kadaluarsa" 
                               name="tanggal_kadaluarsa" 
                               value="{{ old('tanggal_kadaluarsa') }}"
                               required>
                        @error('tanggal_kadaluarsa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Batch Prefix -->
                    <div class="mb-3">
                        <label for="batch_prefix" class="form-label">
                            Prefix Batch <small class="text-muted">(Opsional)</small>
                        </label>
                        <input type="text" 
                               class="form-control @error('batch_prefix') is-invalid @enderror" 
                               id="batch_prefix" 
                               name="batch_prefix" 
                               value="{{ old('batch_prefix', 'BATCH') }}"
                               maxlength="20"
                               onkeyup="updatePreview()">
                        @error('batch_prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Default: BATCH</small>
                    </div>

                   

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="alert alert-warning d-none">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            <div>
                                <strong>Sedang membuat produk...</strong><br>
                                <small>Proses ini bisa memakan waktu beberapa detik tergantung jumlah produk.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-lightning-charge"></i> Generate <span id="qtyText">10</span> Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-set tanggal kadaluarsa 7 hari dari tanggal produksi
    document.getElementById('tanggal_produksi').addEventListener('change', function() {
        const produksi = new Date(this.value);
        const kadaluarsa = new Date(produksi);
        kadaluarsa.setDate(kadaluarsa.getDate() + 7);
        
        const year = kadaluarsa.getFullYear();
        const month = String(kadaluarsa.getMonth() + 1).padStart(2, '0');
        const day = String(kadaluarsa.getDate()).padStart(2, '0');
        
        document.getElementById('tanggal_kadaluarsa').value = `${year}-${month}-${day}`;
    });

    // Update preview batch
    function updatePreview() {
        const qty = parseInt(document.getElementById('quantity').value) || 10;
        const prefix = document.getElementById('batch_prefix').value || 'BATCH';
        const today = new Date();
        const dateStr = today.getFullYear() + 
                       String(today.getMonth() + 1).padStart(2, '0') + 
                       String(today.getDate()).padStart(2, '0');
        
        const lastNum = String(qty).padStart(3, '0');
        document.getElementById('lastBatch').textContent = `${prefix}-${dateStr}-${lastNum}`;
        document.getElementById('qtyText').textContent = qty;
    }

    // Show loading on submit
    document.getElementById('bulkForm').addEventListener('submit', function() {
        document.getElementById('loadingIndicator').classList.remove('d-none');
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    });

    // Update preview on load
    updatePreview();
</script>
@endpush
@endsection