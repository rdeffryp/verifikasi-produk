@extends('layouts.app')

@section('title', 'Dashboard — Z&J Cookies')

@push('styles')
<style>
    .hero-split {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 16px;
        margin-bottom: 24px;
        align-items: stretch;
    }
    .hero-left {
        background: #6B3E26;
        border-radius: 16px;
        padding: 28px 28px 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 170px;
    }
    .hero-eyebrow {
        font-size: 10px;
        color: #D7A86E;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: 30px;
        font-weight: 500;
        color: #FFF3E0;
        line-height: 1.15;
        margin-bottom: 6px;
    }
    .hero-title em { font-style: italic; color: #D7A86E; }
    .hero-desc {
        font-size: 13px;
        color: rgba(255,243,224,.55);
        line-height: 1.5;
        margin-bottom: 20px;
    }
    .hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-hero {
        background: #D7A86E;
        color: #3D1F0A;
        border: none;
        border-radius: 8px;
        padding: 9px 18px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-hero:hover { background: #C89258; color: #3D1F0A; }
    .btn-hero-ghost {
        background: rgba(215,168,110,.15);
        color: #D7A86E;
        border: 1px solid rgba(215,168,110,.3);
        border-radius: 8px;
        padding: 9px 16px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-hero-ghost:hover { background: rgba(215,168,110,.25); color: #D7A86E; }

    /* STAT TILES */
    .stats-grid {
        display: grid;
        grid-template-rows: repeat(2, 1fr);
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .stat-tile {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color .15s;
    }
    .stat-tile:hover { border-color: #D7A86E; }
    .stat-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .si-brown { background: #F5E6CA; color: #6B3E26; }
    .si-green  { background: #DCFCE7; color: #16A34A; }
    .si-amber  { background: #FEF9C3; color: #B45309; }
    .si-red    { background: #FEE2E2; color: #DC2626; }
    .stat-info { flex: 1; min-width: 0; }
    .stat-lbl {
        font-size: 10px;
        color: #9E7555;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 3px;
        white-space: nowrap;
    }
    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        font-weight: 500;
        color: #3D1F0A;
        line-height: 1;
    }

    /* CONTENT GRID */
    .content-grid {
        display: grid;
        grid-template-columns: 210px 1fr;
        gap: 16px;
        align-items: start;
    }

    /* SIDEBAR */
    .sidebar {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 14px;
        padding: 18px;
        position: sticky;
        top: 20px;
    }
    .sidebar-section-label {
        font-size: 10px;
        color: #9E7555;
        text-transform: uppercase;
        letter-spacing: .8px;
        font-weight: 500;
        margin-bottom: 10px;
        display: block;
    }

    /* Search — clean flex container, no overlap */
    .search-form { margin-bottom: 16px; }
    .search-field {
        display: flex;
        align-items: stretch;
        border: 1px solid #EDD9BE;
        border-radius: 8px;
        background: #FFF9F4;
        overflow: hidden;
        transition: border-color .15s;
        height: 36px;
    }
    .search-field:focus-within { border-color: #D7A86E; }
    .search-field-icon {
        width: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #C8A47A;
        pointer-events: none;
    }
    .search-field input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 13px;
        color: #3D1F0A;
        padding: 0 6px 0 0;
        outline: none;
        font-family: 'DM Sans', sans-serif;
        min-width: 0;
    }
    .search-field input::placeholder { color: #C8A47A; }
    .search-field button {
        background: #6B3E26;
        border: none;
        color: #FFF3E0;
        width: 36px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background .15s;
    }
    .search-field button:hover { background: #8B5236; }
    .search-reset {
        display: inline-block;
        margin-top: 5px;
        font-size: 11px;
        color: #9E7555;
        text-decoration: none;
    }
    .search-reset:hover { color: #6B3E26; }

    /* Filter chips */
    .filter-chips { display: flex; flex-direction: column; gap: 3px; }
    .filter-chip {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
        text-decoration: none;
        color: #9E7555;
        transition: all .12s;
    }
    .filter-chip:hover, .filter-chip.active {
        background: #FFF3E0;
        color: #6B3E26;
        border-color: #EDD9BE;
    }
    .filter-chip .dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

    .sidebar-divider { border: none; border-top: 1px solid #EDD9BE; margin: 14px 0; }

    .batch-list { display: flex; flex-direction: column; gap: 2px; }
    .batch-item {
        font-size: 12px;
        color: #9E7555;
        padding: 5px 8px;
        border-radius: 7px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        transition: background .1s;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .batch-item:hover { background: #FFF3E0; color: #6B3E26; }

    /* LIST HEADER */
    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .list-count { font-size: 13px; color: #9E7555; }
    .list-sort {
        font-size: 12px;
        color: #9E7555;
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 7px;
        padding: 5px 10px;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        outline: none;
    }

    /* PRODUCT ROWS */
    .product-row {
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 12px;
        padding: 13px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: border-color .15s, background .15s;
    }
    .product-row:hover { border-color: #D7A86E; background: #FFFBF7; }
    .pr-left { flex: 1; min-width: 0; }
    .pr-top {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 5px;
    }
    .pr-name { font-size: 14px; font-weight: 500; color: #3D1F0A; }
    .pr-id {
        font-size: 11px;
        font-family: 'Courier New', monospace;
        background: #F5E6CA;
        color: #6B3E26;
        padding: 2px 7px;
        border-radius: 5px;
    }
    .pr-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .pr-batch {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: #9E7555;
        text-decoration: none;
    }
    .pr-batch:hover { color: #6B3E26; }
    .pr-date { font-size: 11px; color: #9E7555; }
    .pr-date.expired { color: #DC2626; font-weight: 500; }

    .pr-right {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .pr-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .pb-asli       { background: #DCFCE7; color: #166534; }
    .pb-duplikasi  { background: #FEF9C3; color: #92400E; }
    .pb-kadaluarsa { background: #FEE2E2; color: #991B1B; }
    .pb-belum      { background: #F5E6CA; color: #9E7555; }
    .pb-scan       { background: #E0F2FE; color: #075985; }

    .act-btn {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        border: 1px solid #EDD9BE;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6B3E26;
        text-decoration: none;
        transition: all .15s;
    }
    .act-btn:hover { background: #F5E6CA; border-color: #D7A86E; }
    .act-btn.dl { border-color: #BBF7D0; color: #166534; }
    .act-btn.dl:hover { background: #DCFCE7; }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
        background: #fff;
        border: 1px solid #EDD9BE;
        border-radius: 12px;
    }
    .empty-icon {
        width: 56px; height: 56px;
        background: #FFF3E0;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
    }

    /* PAGINATION */
    .pagi-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #EDD9BE;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pagi-info { font-size: 12px; color: #9E7555; }

    @media (max-width: 900px) {
        .hero-split { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); grid-template-rows: auto; }
    }
    @media (max-width: 640px) {
        .content-grid { grid-template-columns: 1fr; }
        .sidebar { position: static; }
    }
</style>
@endpush

@section('content')

{{-- HERO SPLIT --}}
<div class="hero-split">

    <div class="hero-left">
        <div>
            <div class="hero-eyebrow">Z&amp;J Cookies — Admin Panel</div>
            <div class="hero-title">Dashboard<br><em>Produk</em></div>
        </div>
        <div class="hero-actions">
           <a href="{{ route('products.bulk-create') }}" class="btn-hero-ghost">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Produk
</a>
            <form method="POST" action="{{ route('products.destroy-all') }}" onsubmit="return confirm('Hapus SEMUA produk? Tindakan ini tidak bisa dibatalkan!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-hero-ghost" style="border-color:rgba(220,38,38,.4);color:#FCA5A5;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                    Hapus Semua
                </button>
            </form>
           <a href="{{ route('products.export-excel', ['filter' => '1bulan']) }}" class="btn-hero-ghost">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
    Export 1 Bulan
</a>
<a href="{{ route('products.export-excel', ['filter' => '1minggu']) }}" class="btn-hero-ghost">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
    Export 1 Minggu
</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-tile">
            <div class="stat-icon-wrap si-brown">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-lbl">Total Produk</div>
                <div class="stat-num">{{ $stats['total_products'] }}</div>
            </div>
        </div>

        <div class="stat-tile">
            <div class="stat-icon-wrap si-green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-lbl">Terverifikasi</div>
                <div class="stat-num">{{ $stats['verified_products'] }}</div>
            </div>
        </div>

        <div class="stat-tile">
            <div class="stat-icon-wrap si-amber">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-lbl">Belum Scan</div>
                <div class="stat-num">{{ $stats['unverified_products'] }}</div>
            </div>
        </div>

        <div class="stat-tile">
            <div class="stat-icon-wrap si-red">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-lbl">Kadaluarsa</div>
                <div class="stat-num">{{ $stats['expired_products'] }}</div>
            </div>
        </div>
    </div>

</div>

{{-- CONTENT GRID --}}
<div class="content-grid">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <span class="sidebar-section-label">Cari Produk</span>
        <form method="GET" action="{{ route('products.index') }}" class="search-form">
            <div class="search-field">
                <div class="search-field-icon">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       placeholder="ID, nama, batch..."
                       value="{{ request('search') }}">
                <button type="submit" title="Cari">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="search-reset">
                    ✕ Hapus pencarian
                </a>
            @endif
        </form>

        <span class="sidebar-section-label">Status</span>
        <div class="filter-chips">
           <a href="{{ route('products.index', request()->except('status')) }}"
               class="filter-chip {{ !request('status') ? 'active' : '' }}">
                <span class="dot" style="background:#D7A86E"></span> Semua
            </a>
            <a href="{{ route('products.index', array_merge(request()->all(), ['status'=>'ASLI'])) }}"
               class="filter-chip {{ request('status')=='ASLI' ? 'active' : '' }}">
                <span class="dot" style="background:#16A34A"></span> Asli
            </a>
            <a href="{{ route('products.index', array_merge(request()->all(), ['status'=>'DUPLIKASI'])) }}"
               class="filter-chip {{ request('status')=='DUPLIKASI' ? 'active' : '' }}">
                <span class="dot" style="background:#B45309"></span> Duplikasi
            </a>
            <a href="{{ route('products.index', array_merge(request()->all(), ['status'=>'KADALUARSA'])) }}"
               class="filter-chip {{ request('status')=='KADALUARSA' ? 'active' : '' }}">
                <span class="dot" style="background:#DC2626"></span> Kadaluarsa
            </a>
            <a href="{{ route('products.index', array_merge(request()->all(), ['status'=>'BELUM_DISCAN'])) }}"
               class="filter-chip {{ request('status')=='BELUM_DISCAN' ? 'active' : '' }}">
                <span class="dot" style="background:#C8A47A"></span> Belum Scan
            </a>
        </div>

        <hr class="sidebar-divider">

        <span class="sidebar-section-label">Batch Terbaru</span>
        <div class="batch-list">
            @php
                $recentBatches = \App\Models\Product::select('batch_number')
                    ->whereNotNull('batch_number')
                    ->distinct()
                    ->orderBy('batch_number', 'desc')
                    ->limit(6)
                    ->pluck('batch_number')
                    ->map(function($b) {
                        $parts = explode('-', $b);
                        return count($parts) >= 2 ? $parts[0].'-'.$parts[1] : $b;
                    })
                    ->unique();
            @endphp
            @foreach($recentBatches as $batch)
            <a href="{{ route('products.batch', $batch) }}" class="batch-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                </svg>
                {{ $batch }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- PRODUCT LIST --}}
    <div>
        <div class="list-header">
            <div class="list-count">
                {{ $products->total() }} produk
                @if(request('search'))&nbsp;·&nbsp; hasil "<strong>{{ request('search') }}</strong>"@endif
            </div>
            <select class="list-sort" onchange="window.location=this.value">
                <option value="{{ route('products.index', array_merge(request()->all(), ['sort'=>'newest'])) }}"
                    {{ request('sort','newest')=='newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="{{ route('products.index', array_merge(request()->all(), ['sort'=>'oldest'])) }}"
                    {{ request('sort')=='oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="{{ route('products.index', array_merge(request()->all(), ['sort'=>'scan_count'])) }}"
                    {{ request('sort')=='scan_count' ? 'selected' : '' }}>Scan Terbanyak</option>
            </select>
        </div>

        @if($products->count() > 0)
            @foreach($products as $product)
            @php
                $status = $product->status;
                $statusClass = match($status) {
                    'ASLI'         => 'pb-asli',
                    'DUPLIKASI'    => 'pb-duplikasi',
                    'KADALUARSA'   => 'pb-kadaluarsa',
                    'BELUM_DISCAN' => 'pb-belum',
                    default        => 'pb-belum'
                };
                $statusLabel = match($status) {
                    'BELUM_DISCAN' => 'Belum Scan',
                    default        => ucfirst(strtolower($status))
                };
                $isExpired = \Carbon\Carbon::parse($product->tanggal_kadaluarsa)->isPast();
                $parts = $product->batch_number ? explode('-', $product->batch_number) : [];
                $masterBatch = count($parts) >= 2 ? $parts[0].'-'.$parts[1] : ($product->batch_number ?? '');
            @endphp
            <div class="product-row">
                <div class="pr-left">
                    <div class="pr-top">
                        <span class="pr-name">{{ $product->nama_produk }}</span>
                        <span class="pr-id">{{ $product->product_id }}</span>
                    </div>
                    <div class="pr-meta">
                        @if($product->batch_number)
                        <a href="{{ route('products.batch', $masterBatch) }}" class="pr-batch">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                            </svg>
                            {{ $product->batch_number }}
                        </a>
                        @endif
                        <span class="pr-date {{ $isExpired ? 'expired' : '' }}">
                            Kadaluarsa {{ $product->tanggal_kadaluarsa->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="pr-right">
                    <span class="pr-badge pb-scan">{{ $product->scan_count }}x</span>
                    <span class="pr-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    <a href="{{ route('products.show', $product->id) }}" class="act-btn" title="Lihat Detail">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </a>
                    <a href="{{ route('products.download-qr', $product->id) }}" class="act-btn dl" title="Download QR">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="act-btn" title="Hapus" style="border-color:#FCA5A5;color:#DC2626;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6"/>
                            </svg>
                        </button>
                    </form>
                    </a>
                </div>
            </div>
            @endforeach

            <div class="pagi-wrap">
                <small class="pagi-info">
                    Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }}
                    dari {{ $products->total() }} produk
                </small>
                <div>{{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
            </div>

        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#D7A86E" stroke-width="1.6">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <p style="font-size:14px;color:#9E7555;margin-bottom:14px;">
                    @if(request('search'))
                        Tidak ada produk cocok dengan "{{ request('search') }}"
                    @else
                        Belum ada produk. Silakan tambah produk pertama.
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="btn-hero" style="display:inline-flex;">Lihat Semua Produk</a>
                @else
                   <a href="{{ route('products.bulk-create') }}" class="btn-hero" style="display:inline-flex;">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Produk
</a>
                @endif
            </div>
        @endif
    </div>

</div>

@endsection