@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Produk Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
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
                        <small class="text-muted">Maksimal 100 karakter</small>
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
                        <small class="text-muted">Harus lebih dari tanggal produksi</small>
                    </div>

                    <!-- Batch Number (Optional) -->
                    <div class="mb-3">
                        <label for="batch_number" class="form-label">
                            Nomor Batch <small class="text-muted">(Opsional)</small>
                        </label>
                        <input type="text" 
                               class="form-control @error('batch_number') is-invalid @enderror" 
                               id="batch_number" 
                               name="batch_number" 
                               value="{{ old('batch_number') }}"
                               placeholder="Contoh: BATCH-2025-001">
                        @error('batch_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal 50 karakter</small>
                    </div>

                

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-qr-code"></i> Generate QR Code & Simpan
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
        kadaluarsa.setDate(kadaluarsa.getDate() + 7); // +7 hari default
        
        const year = kadaluarsa.getFullYear();
        const month = String(kadaluarsa.getMonth() + 1).padStart(2, '0');
        const day = String(kadaluarsa.getDate()).padStart(2, '0');
        
        document.getElementById('tanggal_kadaluarsa').value = `${year}-${month}-${day}`;
    });
</script>
@endpush
@endsection