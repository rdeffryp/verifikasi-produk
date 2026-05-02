<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Z&J Cookies — Verifikasi Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        :root {
            --cream:        #FFF3E0;
            --cream-dark:   #F5E6CA;
            --brown-light:  #D7A86E;
            --brown:        #6B3E26;
            --brown-dark:   #3D1F0A;
            --beige-border: #EDD9BE;
            --text-muted:   #9E7555;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--brown);
            padding: 13px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tb-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .tb-logo {
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
            letter-spacing: -0.5px;
        }
        .tb-name {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 500;
            color: var(--cream);
            letter-spacing: 0.2px;
        }
        .tb-sub {
            font-size: 10px;
            color: var(--brown-light);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .tb-badge {
            background: var(--brown-light);
            color: var(--brown);
            font-size: 10px;
            font-weight: 500;
            padding: 3px 11px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        /* ── PAGE WRAPPER ── */
        .page-wrap {
            max-width: 480px;
            margin: 0 auto;
            padding: 28px 16px 48px;
        }

        /* ── HERO ── */
        .hero {
            text-align: center;
            padding: 10px 0 24px;
        }
        .hero-icon {
            width: 64px;
            height: 64px;
            background: var(--brown);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 500;
            color: var(--brown-dark);
            margin-bottom: 6px;
        }
        .hero-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* ── CARD ── */
        .card-box {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--beige-border);
            padding: 22px;
            margin-bottom: 14px;
        }

        /* ── SCANNER AREA ── */
        .scan-placeholder {
            background: #FFF9F2;
            border: 2px dashed var(--brown-light);
            border-radius: 12px;
            height: 240px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .scan-corners {
            position: relative;
            width: 72px;
            height: 72px;
        }
        .corner {
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: var(--brown);
            border-style: solid;
        }
        .corner.tl { top:0; left:0;  border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
        .corner.tr { top:0; right:0; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
        .corner.bl { bottom:0; left:0;  border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
        .corner.br { bottom:0; right:0; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }
        .scan-hint {
            font-size: 13px;
            color: var(--text-muted);
        }

        #reader {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        /* ── BUTTONS ── */
        .btn-primary-custom {
            width: 100%;
            padding: 14px;
            background: var(--brown);
            color: var(--cream);
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s;
        }
        .btn-primary-custom:hover { background: #8B5236; }

        .btn-danger-custom {
            width: 100%;
            padding: 14px;
            background: #C0392B;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s;
        }
        .btn-danger-custom:hover { background: #A93226; }

        .btn-rescan {
            width: 100%;
            padding: 13px;
            background: var(--cream);
            color: var(--brown);
            border: 1.5px solid var(--brown-light);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s;
        }
        .btn-rescan:hover { background: var(--cream-dark); }

        /* ── STEPS ── */
        .steps-row {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }
        .step-item {
            flex: 1;
            background: #FFF9F2;
            border: 1px solid var(--beige-border);
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
        }
        .step-num {
            width: 22px;
            height: 22px;
            background: var(--brown-light);
            color: var(--brown);
            border-radius: 50%;
            font-size: 11px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 6px;
        }
        .step-txt {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* ── RESULT CARDS ── */
        .result-wrap {
            animation: fadeUp .3s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .result-status-card {
            border-radius: 16px;
            padding: 26px 22px 20px;
            text-align: center;
            margin-bottom: 14px;
        }
        .result-status-card.asli       { background: #F0FDF4; border: 1px solid #86EFAC; }
        .result-status-card.duplikasi  { background: #FFFBEB; border: 1px solid #FCD34D; }
        .result-status-card.kadaluarsa { background: #FFF7ED; border: 1px solid #FDBA74; }
        .result-status-card.palsu      { background: #FEF2F2; border: 1px solid #FCA5A5; }
        .result-status-card.error      { background: #FEF2F2; border: 1px solid #FCA5A5; }

        .result-icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        .asli       .result-icon-wrap { background: #DCFCE7; }
        .duplikasi  .result-icon-wrap { background: #FEF9C3; }
        .kadaluarsa .result-icon-wrap { background: #FFEDD5; }
        .palsu      .result-icon-wrap { background: #FEE2E2; }
        .error      .result-icon-wrap { background: #FEE2E2; }

        .result-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .asli       .result-title { color: #166534; }
        .duplikasi  .result-title { color: #92400E; }
        .kadaluarsa .result-title { color: #9A3412; }
        .palsu      .result-title { color: #991B1B; }
        .error      .result-title { color: #991B1B; }

        .result-msg {
            font-size: 13px;
            line-height: 1.5;
        }
        .asli       .result-msg { color: #16A34A; }
        .duplikasi  .result-msg { color: #B45309; }
        .kadaluarsa .result-msg { color: #C2410C; }
        .palsu      .result-msg { color: #DC2626; }
        .error      .result-msg { color: #DC2626; }

        /* ── DETAIL TABLE ── */
        .detail-section-label {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 500;
            margin-bottom: 12px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--beige-border);
            font-size: 13px;
            gap: 12px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--text-muted); flex-shrink: 0; }
        .info-val {
            color: var(--brown-dark);
            font-weight: 500;
            text-align: right;
        }
        .info-val code {
            font-size: 11px;
            background: var(--cream-dark);
            color: var(--brown);
            padding: 2px 7px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
        }
        .badge-scan {
            background: #FEF3C7;
            color: #92400E;
            font-size: 11px;
            padding: 2px 9px;
            border-radius: 20px;
        }

        /* ── SPINNER ── */
        .loading-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--beige-border);
            padding: 36px 22px;
            text-align: center;
            margin-bottom: 14px;
        }
        .spinner-custom {
            width: 36px;
            height: 36px;
            border: 3px solid var(--beige-border);
            border-top-color: var(--brown);
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto 14px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* ── ADMIN LINK ── */
        .admin-link {
            text-align: center;
            margin-top: 24px;
        }
        .admin-link a {
            font-size: 11px;
            color: var(--brown-light);
            opacity: .4;
            text-decoration: none;
        }
        .admin-link a:hover { opacity: .7; }
    </style>
</head>
<body>

    <!-- Topbar -->
    <div class="topbar">
        <div class="tb-brand">
            <div class="tb-logo">Z&J</div>
            <div>
                <div class="tb-name">Z&amp;J Cookies</div>
                <div class="tb-sub">Verifikasi Produk</div>
            </div>
        </div>
        <div class="tb-badge">Konsumen</div>
    </div>

    <div class="page-wrap">

        <!-- Hero -->
        <div class="hero">
            <div class="hero-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#D7A86E" stroke-width="1.8" width="30" height="30">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <path d="M14 14h2v2h-2zM18 14h3M14 18h3M18 18h3v3h-3z"/>
                </svg>
            </div>
            <div class="hero-title">Verifikasi Keaslian Produk</div>
            <div class="hero-subtitle">Scan QR Code pada kemasan untuk memastikan<br>produk Z&amp;J Cookies yang Anda beli asli.</div>
        </div>

        <!-- Scanner Card -->
        <div class="card-box">
            <!-- Placeholder tampil saat scanner belum aktif -->
            <div id="scanPlaceholder" class="scan-placeholder">
                <div class="scan-corners">
                    <div class="corner tl"></div>
                    <div class="corner tr"></div>
                    <div class="corner bl"></div>
                    <div class="corner br"></div>
                </div>
                <div class="scan-hint">Kamera belum aktif</div>
            </div>

            <!-- Reader QR (disembunyikan awal) -->
            <div id="reader" style="display:none;"></div>

            <!-- Tombol -->
            <button id="startBtn" class="btn-primary-custom" onclick="startScanner()">
                <i class="bi bi-camera" style="font-size:16px;"></i>
                Mulai Scan
            </button>
            <button id="stopBtn" class="btn-danger-custom d-none mt-2" onclick="stopScanner()">
                <i class="bi bi-stop-circle" style="font-size:16px;"></i>
                Stop Scanner
            </button>
        </div>

        <!-- Steps -->
        <div class="steps-row">
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-txt">Klik Mulai Scan</div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-txt">Arahkan ke QR Code</div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-txt">Lihat hasil verifikasi</div>
            </div>
        </div>

        <!-- Result Container -->
        <div id="resultContainer"></div>

    </div>

    <!-- Admin Link -->
    <div class="admin-link mb-4">
        <a href="{{ route('admin.login') }}">
            <i class="bi bi-shield-lock"></i> Admin Panel
        </a>
    </div>

    <script>
        let html5QrCode = null;

        function startScanner() {
            document.getElementById('scanPlaceholder').style.display = 'none';
            document.getElementById('reader').style.display = 'block';
            document.getElementById('resultContainer').innerHTML = '';

            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanError
            ).then(() => {
                document.getElementById('startBtn').classList.add('d-none');
                document.getElementById('stopBtn').classList.remove('d-none');
            }).catch(err => {
                document.getElementById('scanPlaceholder').style.display = 'flex';
                document.getElementById('reader').style.display = 'none';
                showError('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
            });
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('startBtn').classList.remove('d-none');
                    document.getElementById('stopBtn').classList.add('d-none');
                    document.getElementById('reader').style.display = 'none';
                    document.getElementById('scanPlaceholder').style.display = 'flex';
                }).catch(err => {
                    console.error('Stop error:', err);
                });
            }
        }

       function onScanSuccess(decodedText, decodedResult) {
            stopScanner();

            // Coba parse sebagai URL dulu (QR baru)
            try {
                const url = new URL(decodedText);
                const parts = url.pathname.split('/');
                const verifyIndex = parts.indexOf('verify');
                if (verifyIndex !== -1 && parts[verifyIndex + 1]) {
                    verifyProduct(parts[verifyIndex + 1]);
                    return;
                }
            } catch (e) {
                // Bukan URL, coba parse sebagai JSON (QR lama)
            }

            // Coba parse sebagai JSON (QR lama)
            try {
                const qrData = JSON.parse(decodedText);
                if (!qrData.product_id) {
                    showError('QR Code tidak mengandung data produk yang valid.');
                    return;
                }
                verifyProduct(qrData.product_id);
                return;
            } catch (e) {
                showError('QR Code tidak valid atau tidak dikenali.');
            }
        }

        function onScanError(errorMessage) {
            // abaikan error scanning biasa
        }

        function verifyProduct(productId) {
            document.getElementById('resultContainer').innerHTML = `
                <div class="loading-card">
                    <div class="spinner-custom"></div>
                    <div class="loading-text">Memverifikasi produk...</div>
                </div>
            `;

            // fetch('{{ route('api.verify') }}', {
            fetch('/api/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'ngrok-skip-browser-warning': 'true'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data);
            })
            .catch(error => {
                showError('Gagal terhubung ke server. Periksa koneksi internet Anda.');
            });
        }

        function showResult(data) {
            let cssClass, iconSvg, statusText;

            switch (data.status) {
                case 'ASLI':
                    cssClass   = 'asli';
                    statusText = 'Produk Asli';
                    iconSvg    = `<svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" width="28" height="28"><path d="M20 6L9 17l-5-5"/></svg>`;
                    break;
                case 'DUPLIKASI':
                    cssClass   = 'duplikasi';
                    statusText = 'Peringatan Duplikasi';
                    iconSvg    = `<svg viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2.5" width="28" height="28"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>`;
                    break;
                case 'KADALUARSA':
                    cssClass   = 'kadaluarsa';
                    statusText = 'Produk Kadaluarsa';
                    iconSvg    = `<svg viewBox="0 0 24 24" fill="none" stroke="#C2410C" stroke-width="2.5" width="28" height="28"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>`;
                    break;
                case 'PALSU':
                    cssClass   = 'palsu';
                    statusText = 'Produk Palsu';
                    iconSvg    = `<svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" width="28" height="28"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>`;
                    break;
                default:
                    cssClass   = 'error';
                    statusText = 'Tidak Diketahui';
                    iconSvg    = `<svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" width="28" height="28"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>`;
            }

            let detailHTML = '';
            if (data.data) {
                detailHTML = `
                    <div class="card-box">
                        <div class="detail-section-label">Detail Produk</div>
                        <div class="info-row">
                            <span class="info-label">ID Produk</span>
                            <span class="info-val"><code>${data.data.product_id}</code></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nama Produk</span>
                            <span class="info-val">${data.data.nama_produk}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tgl Produksi</span>
                            <span class="info-val">${data.data.tanggal_produksi}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tgl Kadaluarsa</span>
                            <span class="info-val">${data.data.tanggal_kadaluarsa}</span>
                        </div>
                        ${data.data.batch_number ? `
                        <div class="info-row">
                            <span class="info-label">Batch</span>
                            <span class="info-val">${data.data.batch_number}</span>
                        </div>` : ''}
                        ${data.data.scan_count ? `
                        <div class="info-row">
                            <span class="info-label">Jumlah Scan</span>
                            <span class="info-val"><span class="badge-scan">${data.data.scan_count}x</span></span>
                        </div>` : ''}
                       ${data.data.first_scan_at ? `
        <div class="info-row">
            <span class="info-label">Pertama Scan</span>
            <span class="info-val">${data.data.first_scan_at}</span>
        </div>` : ''}
    </div>

    ${data.data.hash_data ? `
    <div class="card-box">
        <div class="detail-section-label">Informasi Kriptografi</div>
        <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:6px;">
            <span class="info-label">SHA-256 Hash</span>
            <span style="font-size:11px; font-family:'Courier New',monospace; background:#F5E6CA; color:#6B3E26; padding:6px 10px; border-radius:7px; word-break:break-all; width:100%;">${data.data.hash_data}</span>
        </div>
        <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:6px; border-bottom:none;">
            <span class="info-label">ECDSA Signature</span>
            <span style="font-size:11px; font-family:'Courier New',monospace; background:#F5E6CA; color:#6B3E26; padding:6px 10px; border-radius:7px; word-break:break-all; width:100%;">${data.data.signature}</span>
        </div>
    </div>` : ''}
`;
            }

            document.getElementById('resultContainer').innerHTML = `
                <div class="result-wrap">
                    <div class="result-status-card ${cssClass}">
                        <div class="result-icon-wrap">${iconSvg}</div>
                        <div class="result-title">${statusText}</div>
                        <div class="result-msg">${data.message}</div>
                    </div>
                    ${detailHTML}
                    <button class="btn-rescan" onclick="resetScanner()">
                        <i class="bi bi-arrow-repeat" style="font-size:14px;"></i>
                        Scan Produk Lain
                    </button>
                </div>
            `;
        }

        function showError(message) {
            document.getElementById('resultContainer').innerHTML = `
                <div class="result-wrap">
                    <div class="result-status-card error">
                        <div class="result-icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" width="28" height="28">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M15 9l-6 6M9 9l6 6"/>
                            </svg>
                        </div>
                        <div class="result-title">Terjadi Kesalahan</div>
                        <div class="result-msg">${message}</div>
                    </div>
                    <button class="btn-rescan" onclick="resetScanner()">
                        <i class="bi bi-arrow-repeat" style="font-size:14px;"></i>
                        Coba Lagi
                    </button>
                </div>
            `;
        }

        function resetScanner() {
            document.getElementById('resultContainer').innerHTML = '';
            startScanner();
        }
    </script>

</body>
</html>