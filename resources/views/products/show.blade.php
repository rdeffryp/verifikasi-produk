@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->product_id)

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 500;
        color: #3D1F0A;
    }
    .page-title em { font-style: italic; color: #D7A86E; }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 16px;
        align-items: start;
    }

    .d-card {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 14px;
    }
    .d-card:last-child { margin-bottom: 0; }

    .d-card-head {
        padding: 13px 18px;
        border-bottom: 1px solid #EDD9BE;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .d-card-head-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #6B3E26;
    }
    .d-card-body { padding: 18px; }

    /* Status badges */
    .st-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .st-asli       { background: #DCFCE7; color: #166534; }
    .st-duplikasi  { background: #FEF9C3; color: #92400E; }
    .st-kadaluarsa { background: #FEE2E2; color: #991B1B; }
    .st-belum      { background: #F5E6CA; color: #9E7555; }

    /* Info grid rows */
    .info-grid { display: grid; grid-template-columns: 180px 1fr; }
    .ig-lbl {
        font-size: 12px;
        color: #9E7555;
        padding: 10px 0;
        border-bottom: 1px solid #F5E6CA;
        display: flex;
        align-items: center;
    }
    .ig-val {
        font-size: 13px;
        color: #3D1F0A;
        padding: 10px 0;
        border-bottom: 1px solid #F5E6CA;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .ig-lbl-last, .ig-val-last { border-bottom: none; }

    .mono-pill {
        background: #F5E6CA;
        color: #6B3E26;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        padding: 3px 9px;
        border-radius: 6px;
    }
    .pill-valid   { background: #DCFCE7; color: #166534; font-size: 11px; padding: 3px 9px; border-radius: 20px; }
    .pill-expired { background: #FEE2E2; color: #991B1B; font-size: 11px; padding: 3px 9px; border-radius: 20px; }
    .pill-scan    { background: #E0F2FE; color: #075985; font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }
    .warn-dup     { font-size: 11px; color: #DC2626; display: flex; align-items: center; gap: 4px; }

    /* Crypto fields */
    .crypto-field { margin-bottom: 14px; }
    .crypto-field:last-child { margin-bottom: 0; }
    .crypto-lbl {
        font-size: 11px;
        font-weight: 500;
        color: #9E7555;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 6px;
    }
    .crypto-wrap {
        display: flex;
        align-items: stretch;
        border: 1px solid #EDD9BE;
        border-radius: 8px;
        overflow: hidden;
        background: #FFF9F4;
        transition: border-color .15s;
    }
    .crypto-wrap:focus-within { border-color: #D7A86E; }
    .crypto-wrap input,
    .crypto-wrap textarea {
        flex: 1;
        border: none;
        background: transparent;
        font-family: 'Courier New', monospace;
        font-size: 11px;
        color: #3D1F0A;
        padding: 8px 10px;
        outline: none;
        resize: none;
        min-width: 0;
    }
    .copy-btn {
        width: 36px;
        border: none;
        background: transparent;
        border-left: 1px solid #EDD9BE;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9E7555;
        flex-shrink: 0;
        transition: all .15s;
    }
    .copy-btn:hover { background: #F5E6CA; color: #6B3E26; }

    /* QR frame */
    .qr-frame {
        border: 1px solid #EDD9BE;
        border-radius: 10px;
        background: #fff;
        padding: 14px;
        margin: 0 auto 16px;
        max-width: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qr-frame svg { width: 100%; height: auto; }
    .qr-frame img { width: 100%; height: auto; border-radius: 4px; }

    /* Buttons */
    .btn-primary-brown {
        width: 100%;
        background: #6B3E26;
        color: #FFF3E0;
        border: none;
        border-radius: 8px;
        height: 38px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        margin-bottom: 8px;
        transition: background .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-primary-brown:hover { background: #8B5236; color: #FFF3E0; }

    .btn-outline-brown {
        width: 100%;
        background: transparent;
        color: #6B3E26;
        border: 1px solid #EDD9BE;
        border-radius: 8px;
        height: 38px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        margin-bottom: 8px;
        transition: all .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-outline-brown:hover { background: #F5E6CA; border-color: #D7A86E; color: #6B3E26; }
    .btn-outline-brown:last-child { margin-bottom: 0; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #9E7555;
        text-decoration: none;
        padding: 7px 14px;
        border: 1px solid #EDD9BE;
        border-radius: 8px;
        background: #fff;
        transition: all .15s;
    }
    .btn-back:hover { border-color: #D7A86E; color: #6B3E26; }

    .btn-scanner {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #166534;
        text-decoration: none;
        padding: 7px 14px;
        border: 1px solid #BBF7D0;
        border-radius: 8px;
        background: #fff;
        transition: all .15s;
    }
    .btn-scanner:hover { background: #DCFCE7; color: #166534; }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="{{ route('products.index') }}" class="btn-back">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali
        </a>
        <div class="page-title">Detail <em>Produk</em></div>
    </div>
    <a href="{{ route('scanner') }}" target="_blank" class="btn-scanner">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Buka Scanner
    </a>
</div>

<div class="detail-grid">

    {{-- KOLOM KIRI --}}
    <div>

        {{-- Info Produk --}}
        <div class="d-card">
            <div class="d-card-head">
                <div class="d-card-head-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    Informasi Produk
                </div>
                @php
                    $status = $product->status;
                    $statusClass = match($status) {
                        'ASLI'         => 'st-asli',
                        'DUPLIKASI'    => 'st-duplikasi',
                        'KADALUARSA'   => 'st-kadaluarsa',
                        'BELUM_DISCAN' => 'st-belum',
                        default        => 'st-belum'
                    };
                @endphp
                <span class="st-badge {{ $statusClass }}">{{ $status }}</span>
            </div>
            <div class="d-card-body">
                <div class="info-grid">
                    <div class="ig-lbl">Product ID</div>
                    <div class="ig-val"><span class="mono-pill">{{ $product->product_id }}</span></div>

                    <div class="ig-lbl">Nama Produk</div>
                    <div class="ig-val" style="font-weight:500;font-size:14px">{{ $product->nama_produk }}</div>

                    <div class="ig-lbl">Tanggal Produksi</div>
                    <div class="ig-val">{{ $product->tanggal_produksi->format('d F Y') }}</div>

                    <div class="ig-lbl">Tanggal Kadaluarsa</div>
                    <div class="ig-val">
                        {{ $product->tanggal_kadaluarsa->format('d F Y') }}
                        @if($product->isExpired())
                            <span class="pill-expired">Sudah Kadaluarsa</span>
                        @else
                            <span class="pill-valid">Masih Valid</span>
                        @endif
                    </div>

                    @if($product->batch_number)
                    <div class="ig-lbl">Nomor Batch</div>
                    <div class="ig-val"><span class="mono-pill">{{ $product->batch_number }}</span></div>
                    @endif

                    <div class="ig-lbl">Status Verifikasi</div>
                    <div class="ig-val">
                        @if($product->is_verified)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Sudah Terverifikasi
                        @else
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#D7A86E" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Belum Pernah Di-scan
                        @endif
                    </div>

                    <div class="ig-lbl">Jumlah Scan</div>
                    <div class="ig-val">
                        <span class="pill-scan">{{ $product->scan_count }}x</span>
                        @if($product->scan_count > 1)
                            <span class="warn-dup">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Kemungkinan duplikasi QR
                            </span>
                        @endif
                    </div>

                    @if($product->first_scan_at)
                    <div class="ig-lbl">Scan Pertama</div>
                    <div class="ig-val">{{ $product->first_scan_at->format('d F Y, H:i') }} WIB</div>
                    @endif

                    @if($product->last_scan_at)
                    <div class="ig-lbl ig-lbl-last">Scan Terakhir</div>
                    <div class="ig-val ig-val-last">{{ $product->last_scan_at->format('d F Y, H:i') }} WIB</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kriptografi --}}
        <div class="d-card">
            <div class="d-card-head">
                <div class="d-card-head-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Informasi Kriptografi
                </div>
            </div>
            <div class="d-card-body">
                <div class="crypto-field">
                    <div class="crypto-lbl">SHA-256 Hash</div>
                    <div class="crypto-wrap">
                        <input type="text" value="{{ $product->hash_data }}" readonly>
                        <button class="copy-btn" type="button" onclick="copyToClipboard('{{ $product->hash_data }}')" title="Salin">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                    </div>
                </div>
                <div class="crypto-field">
                    <div class="crypto-lbl">ECDSA Signature</div>
                    <div class="crypto-wrap">
                        <textarea rows="3" readonly>{{ $product->signature }}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div>

        {{-- QR Code --}}
        <div class="d-card">
            <div class="d-card-head">
                <div class="d-card-head-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    QR Code
                </div>
            </div>
            <div class="d-card-body" style="text-align:center">
                @if($product->qr_code_path && file_exists(public_path($product->qr_code_path)))
                    <div class="qr-frame">
                        @if(str_ends_with($product->qr_code_path, '.svg'))
                            {!! file_get_contents(public_path($product->qr_code_path)) !!}
                        @else
                            <img src="{{ asset($product->qr_code_path) }}" alt="QR Code {{ $product->product_id }}">
                        @endif
                    </div>
                    <a href="{{ route('products.download-qr', $product->id) }}" class="btn-primary-brown">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download QR Code
                    </a>
                    <a href="{{ route('products.download-label', $product->id) }}" class="btn-outline-brown">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        Download Label
                    </a>
                    <button class="btn-outline-brown" onclick="printQR()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print QR Code
                    </button>
                @else
                    <div style="background:#FEF9C3;border:1px solid #FDE68A;border-radius:8px;padding:12px 14px;font-size:13px;color:#92400E;display:flex;align-items:center;gap:8px">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        QR Code tidak ditemukan
                    </div>
                @endif
            </div>
        </div>

        {{-- Aksi --}}
        <div class="d-card">
            <div class="d-card-head">
                <div class="d-card-head-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                    Aksi
                </div>
            </div>
            <div class="d-card-body">
                <a href="{{ route('products.index') }}" class="btn-outline-brown">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Kembali ke Daftar
                </a>
                <a href="{{ route('scanner') }}" target="_blank" class="btn-outline-brown" style="color:#166534;border-color:#BBF7D0">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Buka Scanner
                </a>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Hash berhasil di-copy!');
        }, function(err) {
            alert('Gagal copy: ' + err);
        });
    }

    function printQR() {
        const qrContainer = document.querySelector('.qr-frame');
        if (!qrContainer) {
            alert('QR Code tidak ditemukan!');
            return;
        }

        const qrContent = qrContainer.innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print QR Code - {{ $product->product_id }}</title>
                <style>
                    body { text-align: center; padding: 20px; font-family: Arial, sans-serif; }
                    .qr-container { max-width: 400px; margin: 20px auto; border: 2px solid #000; padding: 20px; }
                    svg { width: 100%; height: auto; }
                    h2 { margin: 20px 0; }
                    p { margin: 5px 0; }
                </style>
            </head>
            <body>
                <h2>{{ $product->nama_produk }}</h2>
                <div class="qr-container">${qrContent}</div>
                <p><strong>Product ID:</strong> {{ $product->product_id }}</p>
                <p><strong>Produksi:</strong> {{ $product->tanggal_produksi->format('d F Y') }}</p>
                <p><strong>Kadaluarsa:</strong> {{ $product->tanggal_kadaluarsa->format('d F Y') }}</p>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 100);
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush

@endsection