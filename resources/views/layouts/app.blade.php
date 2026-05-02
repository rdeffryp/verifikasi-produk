<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Z&J Cookies — Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --cream:         #FFF3E0;
            --cream-dark:    #F5E6CA;
            --beige-border:  #EDD9BE;
            --brown-light:   #D7A86E;
            --brown:         #6B3E26;
            --brown-dark:    #3D1F0A;
            --text-muted:    #9E7555;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            min-height: 100vh;
            margin: 0;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--brown) !important;
            padding: 0 0;
            border-bottom: 3px solid var(--brown-light);
        }
        .navbar .container {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-logo {
            width: 36px;
            height: 36px;
            background: var(--brown-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--brown);
            flex-shrink: 0;
        }
        .brand-text-wrap .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 500;
            color: var(--cream);
            line-height: 1.1;
            display: block;
        }
        .brand-text-wrap .brand-sub {
            font-size: 10px;
            color: var(--brown-light);
            letter-spacing: 1px;
            text-transform: uppercase;
            display: block;
        }
        .navbar-toggler {
            border-color: var(--brown-light);
        }
        .navbar-toggler-icon {
            filter: invert(70%) sepia(30%) saturate(500%) hue-rotate(10deg);
        }

        /* Nav links */
        .navbar-nav .nav-link {
            color: rgba(255,243,224,0.75) !important;
            font-size: 14px;
            font-weight: 400;
            padding: 8px 12px !important;
            border-radius: 8px;
            transition: background .15s, color .15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--cream) !important;
            background: rgba(215,168,110,.18);
        }
        .navbar-nav .nav-link.active {
            background: rgba(215,168,110,.25);
            color: var(--brown-light) !important;
        }
        .nav-divider {
            width: 1px;
            height: 24px;
            background: rgba(255,243,224,.15);
            margin: auto 4px;
        }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid var(--beige-border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(107,62,38,.12);
            padding: 6px;
            min-width: 200px;
        }
        .dropdown-item {
            border-radius: 8px;
            font-size: 14px;
            padding: 8px 12px;
            color: var(--brown-dark);
        }
        .dropdown-item:hover { background: var(--cream); }
        .dropdown-item.text-danger:hover { background: #FEF2F2; }
        .dropdown-item-text {
            font-size: 12px;
            color: var(--text-muted);
            padding: 6px 12px;
        }
        .dropdown-divider { border-color: var(--beige-border); }

        /* ── MAIN CONTENT ── */
        .main-content {
            padding: 32px 0 48px;
            min-height: calc(100vh - 140px);
        }

        /* ── CARDS ── */
        .card {
            border: 1px solid var(--beige-border) !important;
            border-radius: 16px !important;
            box-shadow: none !important;
            background: #fff;
            margin-bottom: 20px;
        }
        .card-header {
            background: #fff !important;
            border-bottom: 1px solid var(--beige-border) !important;
            border-radius: 16px 16px 0 0 !important;
            font-weight: 500;
            font-size: 15px;
            color: var(--brown-dark);
            padding: 16px 20px !important;
        }
        .card-body { padding: 20px !important; }

        /* Stat cards */
        .stat-card {
            border-left: 3px solid !important;
            transition: transform .2s;
            cursor: default;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-card.primary { border-left-color: var(--brown) !important; }
        .stat-card.success { border-left-color: #16A34A !important; }
        .stat-card.warning { border-left-color: #D97706 !important; }
        .stat-card.danger  { border-left-color: #DC2626 !important; }

        .stat-card .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .stat-card .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 500;
            color: var(--brown-dark);
            line-height: 1;
        }
        .stat-card .stat-icon {
            font-size: 28px;
            opacity: .25;
        }
        .stat-card.primary .stat-icon { color: var(--brown); }
        .stat-card.success .stat-icon { color: #16A34A; }
        .stat-card.warning .stat-icon { color: #D97706; }
        .stat-card.danger  .stat-icon { color: #DC2626; }

        /* ── TABLE ── */
        .table {
            font-size: 14px;
            color: var(--brown-dark);
        }
        .table thead th {
            background: var(--cream);
            color: var(--text-muted);
            font-weight: 500;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1px solid var(--beige-border) !important;
            padding: 10px 14px;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #FFF3E0 !important;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover td { background: #FFFBF5; }

        /* ── BADGES ── */
        .badge {
            font-weight: 500 !important;
            font-size: 11px !important;
            padding: 4px 10px !important;
            border-radius: 20px !important;
        }
        .badge.bg-success    { background: #DCFCE7 !important; color: #166534 !important; }
        .badge.bg-warning    { background: #FEF9C3 !important; color: #92400E !important; }
        .badge.bg-danger     { background: #FEE2E2 !important; color: #991B1B !important; }
        .badge.bg-secondary  { background: var(--cream-dark) !important; color: var(--brown) !important; }
        .badge.bg-info       { background: #E0F2FE !important; color: #075985 !important; }

        /* ── BUTTONS ── */
        .btn {
            border-radius: 8px !important;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px !important;
            transition: all .15s;
        }
        .btn-primary {
            background: var(--brown) !important;
            border-color: var(--brown) !important;
            color: var(--cream) !important;
        }
        .btn-primary:hover {
            background: #8B5236 !important;
            border-color: #8B5236 !important;
        }
        .btn-outline-primary {
            border-color: var(--brown) !important;
            color: var(--brown) !important;
            background: transparent !important;
        }
        .btn-outline-primary:hover {
            background: var(--brown) !important;
            color: var(--cream) !important;
        }
        .btn-outline-secondary {
            border-color: var(--beige-border) !important;
            color: var(--text-muted) !important;
            background: transparent !important;
        }
        .btn-outline-secondary:hover {
            background: var(--cream-dark) !important;
            color: var(--brown) !important;
        }
        .btn-outline-success {
            border-color: #86EFAC !important;
            color: #166534 !important;
            background: transparent !important;
        }
        .btn-outline-success:hover {
            background: #DCFCE7 !important;
            color: #166534 !important;
        }
        .btn-sm { padding: 6px 12px !important; font-size: 13px !important; }

        /* ── FORM CONTROLS ── */
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 1px solid var(--beige-border) !important;
            font-size: 14px;
            padding: 9px 14px !important;
            color: var(--brown-dark);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brown-light) !important;
            box-shadow: 0 0 0 3px rgba(215,168,110,.15) !important;
            outline: none;
        }
        .form-control::placeholder { color: #C8A47A; }

        /* ── ALERTS ── */
        .alert {
            border-radius: 10px !important;
            border: none !important;
            font-size: 14px;
            padding: 12px 16px !important;
        }
        .alert-success { background: #F0FDF4 !important; color: #166534 !important; border-left: 3px solid #16A34A !important; }
        .alert-danger  { background: #FEF2F2 !important; color: #991B1B !important; border-left: 3px solid #DC2626 !important; }

        /* ── PAGINATION ── */
        .pagination { gap: 4px; }
        .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--beige-border) !important;
            color: var(--brown) !important;
            font-size: 13px;
            padding: 6px 11px !important;
            background: #fff;
            transition: all .15s;
        }
        .page-link:hover { background: var(--cream) !important; }
        .page-item.active .page-link {
            background: var(--brown) !important;
            border-color: var(--brown) !important;
            color: var(--cream) !important;
        }
        .page-item.disabled .page-link {
            background: var(--cream) !important;
            color: var(--beige-border) !important;
        }

        /* ── FOOTER ── */
        .footer {
            background: #fff;
            border-top: 1px solid var(--beige-border);
            padding: 18px 0;
            margin-top: 20px;
        }
        .footer p {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        /* ── CODE ── */
        code {
            background: var(--cream-dark);
            color: var(--brown);
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <div class="brand-logo">Z&J</div>
                <div class="brand-text-wrap">
                    <span class="brand-name">Z&amp;J Cookies</span>
                    <span class="brand-sub">Admin Panel</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"
                           href="{{ route('products.index') }}">
                            <i class="bi bi-grid" style="font-size:14px;"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.bulk-create') ? 'active' : '' }}"
                           href="{{ route('products.bulk-create') }}">
                            <i class="bi bi-lightning-charge" style="font-size:14px;"></i> Tambah Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('scanner') }}" target="_blank">
                            <i class="bi bi-qr-code-scan" style="font-size:14px;"></i> Scanner
                        </a>
                    </li>

                    <li class="nav-item"><div class="nav-divider d-none d-lg-block"></div></li>

                    @if(Auth::guard('admin')->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle" style="font-size:14px;"></i>
                            {{ Auth::guard('admin')->user()->full_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <i class="bi bi-person me-1"></i>
                                    {{ Auth::guard('admin')->user()->username }}
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i><strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p><i class="bi bi-cookie me-1"></i> Z&amp;J Cookies — Sistem Verifikasi Produk</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; {{ date('Y') }} — Secured by ECDSA Digital Signature</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>