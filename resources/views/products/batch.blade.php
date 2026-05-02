@extends('layouts.app')

@section('title', 'Batch: ' . $batchNumber)

@push('styles')
<style>
    .batch-hero {
        background: #6B3E26;
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .batch-hero-left { flex: 1; min-width: 0; }
    .batch-eyebrow {
        font-size: 10px;
        color: #D7A86E;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .batch-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 500;
        color: #FFF3E0;
        margin-bottom: 3px;
    }
    .batch-title em { font-style: italic; color: #D7A86E; }
    .batch-subtitle { font-size: 13px; color: rgba(255,243,224,.5); }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: border-color .15s;
    }
    .stat-card:hover { border-color: #D7A86E; }
    .stat-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .si-brown { background: #F5E6CA; color: #6B3E26; }
    .si-green  { background: #DCFCE7; color: #16A34A; }
    .si-blue   { background: #E0F2FE; color: #075985; }
    .si-red    { background: #FEE2E2; color: #DC2626; }
    .stat-info { flex: 1; min-width: 0; }
    .stat-lbl {
        font-size: 11px;
        color: #9E7555;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 3px;
    }
    .stat-val {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 500;
        color: #3D1F0A;
        line-height: 1;
    }

    .action-bar {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-print {
        flex: 1;
        min-width: 160px;
        background: #6B3E26;
        color: #FFF3E0;
        border: none;
        border-radius: 8px;
        height: 40px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-print:hover { background: #8B5236; color: #FFF3E0; }
    .btn-download {
        flex: 1;
        min-width: 160px;
        background: #16A34A;
        color: #fff;
        border: none;
        border-radius: 8px;
        height: 40px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-download:hover { background: #15803D; color: #fff; }
    .btn-back {
        flex: 1;
        min-width: 160px;
        background: transparent;
        color: #6B3E26;
        border: 1px solid #EDD9BE;
        border-radius: 8px;
        height: 40px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        transition: all .15s;
    }
    .btn-back:hover { background: #F5E6CA; border-color: #D7A86E; color: #6B3E26; }

    .table-card {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 14px;
        overflow: hidden;
    }
    .table-card-head {
        padding: 14px 18px;
        border-bottom: 1px solid #EDD9BE;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #6B3E26;
    }
    .batch-table {
        width: 100%;
        border-collapse: collapse;
    }
    .batch-table thead tr {
        background: #FFF9F4;
    }
    .batch-table thead th {
        font-size: 11px;
        font-weight: 500;
        color: #9E7555;
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: 10px 16px;
        border-bottom: 1px solid #EDD9BE;
        white-space: nowrap;
    }
    .batch-table tbody tr {
        border-bottom: 1px solid #F5E6CA;
        transition: background .12s;
    }
    .batch-table tbody tr:last-child { border-bottom: none; }
    .batch-table tbody tr:hover { background: #FFFBF7; }
    .batch-table tbody td {
        padding: 11px 16px;
        font-size: 13px;
        color: #3D1F0A;
        vertical-align: middle;
    }
    .td-num { color: #C8A47A; font-size: 12px; }

    .mono-pill {
        background: #F5E6CA;
        color: #6B3E26;
        font-family: 'Courier New', monospace;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .batch-pill {
        background: #F1EFE8;
        color: #5F5E5A;
        font-size: 11px;
        padding: 3px 9px;
        border-radius: 20px;
        font-family: 'Courier New', monospace;
    }
    .scan-pill {
        background: #E0F2FE;
        color: #075985;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 9px;
        border-radius: 20px;
    }
    .st-asli       { background: #DCFCE7; color: #166534; font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
    .st-duplikasi  { background: #FEF9C3; color: #92400E; font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
    .st-kadaluarsa { background: #FEE2E2; color: #991B1B; font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
    .st-belum      { background: #F5E6CA; color: #9E7555; font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }

    .act-btn {
        width: 30px; height: 30px;
        border-radius: 7px;
        border: 1px solid #EDD9BE;
        background: transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #6B3E26;
        text-decoration: none;
        transition: all .15s;
    }
    .act-btn:hover { background: #F5E6CA; border-color: #D7A86E; }
    .act-btn.dl { border-color: #BBF7D0; color: #166534; }
    .act-btn.dl:hover { background: #DCFCE7; }

    @media (max-width: 900px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .action-bar { flex-direction: column; }
    }
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="batch-hero">
    <div class="batch-hero-left">
        <div class="batch-eyebrow">Batch Produk</div>
        <div class="batch-title"><em>{{ $batchNumber }}</em></div>
        <div class="batch-subtitle">{{ $batchInfo['nama_produk'] }}</div>
    </div>
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(215,168,110,.3)" stroke-width="1.2">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
        <line x1="12" y1="22.08" x2="12" y2="12"/>
    </svg>
</div>

{{-- STATS --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon si-brown">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-lbl">Total Produk</div>
            <div class="stat-val">{{ $batchInfo['total_products'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-lbl">Terverifikasi</div>
            <div class="stat-val" style="color:#16A34A">{{ $batchInfo['verified_count'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-lbl">Total Scan</div>
            <div class="stat-val" style="color:#075985">{{ $batchInfo['total_scans'] }}x</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-lbl">Kadaluarsa</div>
            <div class="stat-val" style="font-size:16px;padding-top:4px">{{ $batchInfo['tanggal_kadaluarsa']->format('d M Y') }}</div>
        </div>
    </div>
</div>

{{-- ACTION BAR --}}
<div class="action-bar">
    <a href="{{ route('products.batch-print', $batchNumber) }}" class="btn-print" target="_blank">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Print Semua QR Code
    </a>
    <a href="{{ route('products.bulk-download-qr', $batchNumber) }}" class="btn-download">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Download ZIP ({{ $batchInfo['total_products'] }} QR)
    </a>
    <a href="{{ route('products.bulk-download-label', $batchNumber) }}" class="btn-download" style="background:#7C3AED">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
        Download ZIP Label ({{ $batchInfo['total_products'] }})
    </a>
    <form method="POST" action="{{ route('products.destroy-batch', $batchNumber) }}" onsubmit="return confirm('Hapus semua produk dalam batch {{ $batchNumber }}?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-back" style="border-color:#FCA5A5;color:#DC2626;min-width:160px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            Hapus Batch Ini
        </button>
    </form>
    <a href="{{ route('products.index') }}" class="btn-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Kembali ke Daftar
    </a>
</div>

{{-- TABLE --}}
<div class="table-card">
    <div class="table-card-head">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        Daftar Produk dalam Batch
    </div>
    <div style="overflow-x:auto">
        <table class="batch-table">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Product ID</th>
                    <th>Batch Number</th>
                    <th>Status</th>
                    <th>Scan Count</th>
                    <th style="width:90px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td><span class="mono-pill">{{ $product->product_id }}</span></td>
                    <td><span class="batch-pill">{{ $product->batch_number }}</span></td>
                    <td>
                        @php
                            $status = $product->status;
                            $stClass = match($status) {
                                'ASLI'         => 'st-asli',
                                'DUPLIKASI'    => 'st-duplikasi',
                                'KADALUARSA'   => 'st-kadaluarsa',
                                'BELUM_DISCAN' => 'st-belum',
                                default        => 'st-belum'
                            };
                            $stLabel = match($status) {
                                'BELUM_DISCAN' => 'Belum Scan',
                                default        => ucfirst(strtolower($status))
                            };
                        @endphp
                        <span class="{{ $stClass }}">{{ $stLabel }}</span>
                    </td>
                    <td><span class="scan-pill">{{ $product->scan_count }}x</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('products.show', $product->id) }}" class="act-btn" title="Detail">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="{{ route('products.download-qr', $product->id) }}" class="act-btn dl" title="Download QR">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </a>
                            <a href="{{ route('products.download-label', $product->id) }}" class="act-btn" title="Download Label" style="border-color:#DDD6FE;color:#7C3AED">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="act-btn" title="Hapus" style="border-color:#FCA5A5;color:#DC2626;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection