<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Z&J Cookies — Verifikasi Produk</title>

    {{-- DEPENDENCIES (tidak diubah) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- FONT UPGRADE --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">

    <style>
    /* ═══════════════════════════════════════════════════════
       ROOT TOKENS — Z&J PREMIUM PALETTE
       (warna dasar brand dipertahankan, di-enhance)
    ═══════════════════════════════════════════════════════ */
    :root {
        /* Brand colors — enhanced */
        --cream:          #FDF6EC;
        --cream-deep:     #F5E9D0;
        --cream-glass:    rgba(253,246,236,0.7);
        --brown-light:    #C9935A;
        --brown:          #6B3E26;
        --brown-mid:      #8B5535;
        --brown-dark:     #2E1508;
        --beige-border:   #E8D0B0;
        --text-muted:     #A07850;
        --warm-glow:      rgba(201,147,90,0.12);
        --warm-glow2:     rgba(201,147,90,0.06);

        /* Status colors — premium versions */
        --success-light:  #EDFAF4;
        --success-mid:    #22C55E;
        --success-text:   #14532D;
        --success-glow:   rgba(34,197,94,0.15);

        --warning-light:  #FFFBEB;
        --warning-mid:    #F59E0B;
        --warning-text:   #78350F;
        --warning-glow:   rgba(245,158,11,0.15);

        --danger-light:   #FEF2F2;
        --danger-mid:     #EF4444;
        --danger-text:    #7F1D1D;
        --danger-glow:    rgba(239,68,68,0.15);

        --orange-light:   #FFF7ED;
        --orange-mid:     #F97316;
        --orange-text:    #7C2D12;
        --orange-glow:    rgba(249,115,22,0.15);

        /* UI */
        --radius-sm:  8px;
        --radius-md:  14px;
        --radius-lg:  20px;
        --radius-xl:  28px;
        --shadow-soft: 0 2px 20px rgba(107,62,38,0.08), 0 1px 4px rgba(107,62,38,0.06);
        --shadow-card: 0 8px 40px rgba(107,62,38,0.12), 0 2px 8px rgba(107,62,38,0.06);
        --shadow-glow: 0 0 60px rgba(201,147,90,0.1);
        --transition: cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* ═══════════════════════════════════════════════════════
       RESET & BASE
    ═══════════════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; }

    body {
        background-color: var(--cream);
        font-family: 'DM Sans', sans-serif;
        font-weight: 300;
        min-height: 100vh;
        margin: 0; padding: 0;
        overflow-x: hidden;
        position: relative;
    }

    /* Ambient texture */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% -10%, rgba(201,147,90,0.08) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 90% 110%, rgba(107,62,38,0.06) 0%, transparent 60%),
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    body > * { position: relative; z-index: 1; }

    /* ═══════════════════════════════════════════════════════
       TOPBAR — PREMIUM REDESIGN
    ═══════════════════════════════════════════════════════ */
    .topbar {
        background: linear-gradient(135deg, var(--brown-dark) 0%, var(--brown) 60%, var(--brown-mid) 100%);
        padding: 0 24px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 0 rgba(201,147,90,0.2), var(--shadow-soft);
        backdrop-filter: blur(20px);
    }

    /* Subtle shimmer line on topbar */
    .topbar::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(201,147,90,0.5) 30%, rgba(201,147,90,0.8) 50%, rgba(201,147,90,0.5) 70%, transparent);
    }

    .tb-brand { display: flex; align-items: center; gap: 12px; }

    .tb-logo {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, var(--brown-light), #E8B07A);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif;
        font-size: 14px; font-weight: 600;
        color: var(--brown-dark);
        letter-spacing: -0.3px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.25);
        flex-shrink: 0;
    }

    .tb-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 18px; font-weight: 500;
        color: var(--cream);
        letter-spacing: 0.2px;
        line-height: 1.1;
    }

    .tb-sub {
        font-size: 10px; font-weight: 400;
        color: var(--brown-light);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        opacity: 0.85;
    }

    .tb-badge {
        background: rgba(201,147,90,0.15);
        border: 1px solid rgba(201,147,90,0.3);
        color: var(--brown-light);
        font-size: 10px; font-weight: 500;
        padding: 5px 14px;
        border-radius: 100px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }

    /* ═══════════════════════════════════════════════════════
       PAGE LAYOUT
    ═══════════════════════════════════════════════════════ */
    .page-wrap {
        max-width: 500px;
        margin: 0 auto;
        padding: 36px 20px 60px;
    }

    /* ═══════════════════════════════════════════════════════
       STATUS CARD — PREMIUM REDESIGN
    ═══════════════════════════════════════════════════════ */
    .result-status-card {
        border-radius: var(--radius-xl);
        padding: 36px 28px 30px;
        text-align: center;
        margin-bottom: 18px;
        position: relative;
        overflow: hidden;
        animation: cardReveal 0.7s var(--transition) both;
    }

    @keyframes cardReveal {
        from { opacity: 0; transform: translateY(24px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Glassmorphism base */
    .result-status-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(145deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.3) 100%);
        border-radius: inherit;
        pointer-events: none;
    }

    /* Glow blob behind card */
    .result-status-card::after {
        content: '';
        position: absolute;
        width: 200px; height: 200px;
        border-radius: 50%;
        top: -60px; right: -40px;
        filter: blur(60px);
        opacity: 0.5;
        pointer-events: none;
    }

    .result-status-card.asli {
        background: linear-gradient(145deg, #F0FDF6, #DFFAEC);
        border: 1px solid rgba(134,239,172,0.5);
        box-shadow: 0 0 0 1px rgba(34,197,94,0.08), var(--shadow-card), 0 0 80px rgba(34,197,94,0.08);
    }
    .result-status-card.asli::after { background: var(--success-mid); }

    .result-status-card.duplikasi {
        background: linear-gradient(145deg, #FFFCEB, #FEF7D0);
        border: 1px solid rgba(252,211,77,0.5);
        box-shadow: 0 0 0 1px rgba(245,158,11,0.08), var(--shadow-card), 0 0 80px rgba(245,158,11,0.08);
    }
    .result-status-card.duplikasi::after { background: var(--warning-mid); }

    .result-status-card.kadaluarsa {
        background: linear-gradient(145deg, #FFF9F5, #FFF0E5);
        border: 1px solid rgba(253,186,116,0.5);
        box-shadow: 0 0 0 1px rgba(249,115,22,0.08), var(--shadow-card), 0 0 80px rgba(249,115,22,0.08);
    }
    .result-status-card.kadaluarsa::after { background: var(--orange-mid); }

    .result-status-card.palsu {
        background: linear-gradient(145deg, #FFF5F5, #FEE9E9);
        border: 1px solid rgba(252,165,165,0.5);
        box-shadow: 0 0 0 1px rgba(239,68,68,0.08), var(--shadow-card), 0 0 80px rgba(239,68,68,0.08);
    }
    .result-status-card.palsu::after { background: var(--danger-mid); }

    /* Icon wrapper */
    .result-icon-wrap {
        width: 68px; height: 68px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 18px;
        position: relative;
        z-index: 1;
    }

    .asli       .result-icon-wrap { background: rgba(34,197,94,0.12);  box-shadow: 0 0 0 8px rgba(34,197,94,0.06),  0 0 0 16px rgba(34,197,94,0.03); }
    .duplikasi  .result-icon-wrap { background: rgba(245,158,11,0.12); box-shadow: 0 0 0 8px rgba(245,158,11,0.06), 0 0 0 16px rgba(245,158,11,0.03); }
    .kadaluarsa .result-icon-wrap { background: rgba(249,115,22,0.12); box-shadow: 0 0 0 8px rgba(249,115,22,0.06), 0 0 0 16px rgba(249,115,22,0.03); }
    .palsu      .result-icon-wrap { background: rgba(239,68,68,0.12);  box-shadow: 0 0 0 8px rgba(239,68,68,0.06),  0 0 0 16px rgba(239,68,68,0.03); }

    /* Animated checkmark for ASLI */
    .check-svg circle {
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: drawCircle 0.5s 0.3s var(--transition) forwards;
    }
    .check-svg path {
        stroke-dasharray: 50;
        stroke-dashoffset: 50;
        animation: drawCheck 0.4s 0.7s var(--transition) forwards;
    }
    @keyframes drawCircle {
        to { stroke-dashoffset: 0; }
    }
    @keyframes drawCheck {
        to { stroke-dashoffset: 0; }
    }

    /* Shake animation for PALSU */
    .palsu .result-icon-wrap {
        animation: cardReveal 0.7s var(--transition) both, shakeIcon 0.5s 0.8s ease both;
    }
    @keyframes shakeIcon {
        0%,100% { transform: translateX(0) rotate(0deg); }
        20%      { transform: translateX(-5px) rotate(-2deg); }
        40%      { transform: translateX(5px) rotate(2deg); }
        60%      { transform: translateX(-4px) rotate(-1deg); }
        80%      { transform: translateX(3px) rotate(1deg); }
    }

    .result-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 26px; font-weight: 500;
        letter-spacing: 0.2px;
        margin-bottom: 8px;
        position: relative; z-index: 1;
        line-height: 1.1;
    }
    .asli       .result-title { color: var(--success-text); }
    .duplikasi  .result-title { color: var(--warning-text); }
    .kadaluarsa .result-title { color: var(--orange-text); }
    .palsu      .result-title { color: var(--danger-text); }

    .result-msg {
        font-size: 13.5px; line-height: 1.6;
        font-weight: 300;
        position: relative; z-index: 1;
        max-width: 300px; margin: 0 auto;
    }
    .asli       .result-msg { color: rgba(20,83,45,0.8); }
    .duplikasi  .result-msg { color: rgba(120,53,15,0.8); }
    .kadaluarsa .result-msg { color: rgba(124,45,18,0.8); }
    .palsu      .result-msg { color: rgba(127,29,29,0.8); }

    /* Pulse ring for ASLI */
    .asli .result-icon-wrap::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 1.5px solid rgba(34,197,94,0.3);
        animation: pulseRing 2.5s ease-in-out 1s infinite;
    }
    @keyframes pulseRing {
        0%   { transform: scale(1); opacity: 0.7; }
        50%  { transform: scale(1.12); opacity: 0.2; }
        100% { transform: scale(1); opacity: 0.7; }
    }

    /* ═══════════════════════════════════════════════════════
       CARD BOX — GLASSMORPHISM
    ═══════════════════════════════════════════════════════ */
    .card-box {
        background: rgba(255,255,255,0.72);
        backdrop-filter: blur(20px) saturate(1.4);
        -webkit-backdrop-filter: blur(20px) saturate(1.4);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(232,208,176,0.6);
        padding: 24px;
        margin-bottom: 14px;
        box-shadow: var(--shadow-soft), inset 0 1px 0 rgba(255,255,255,0.9);
        animation: cardReveal 0.7s var(--transition) both;
    }
    .card-box:nth-child(2) { animation-delay: 0.08s; }
    .card-box:nth-child(3) { animation-delay: 0.16s; }

    /* ═══════════════════════════════════════════════════════
       SECTION LABEL
    ═══════════════════════════════════════════════════════ */
    .detail-section-label {
        font-size: 10px; font-weight: 500;
        color: var(--text-muted);
        letter-spacing: 1.8px;
        text-transform: uppercase;
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .detail-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--beige-border), transparent);
    }

    /* ═══════════════════════════════════════════════════════
       INFO ROWS
    ═══════════════════════════════════════════════════════ */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 0;
        border-bottom: 1px solid rgba(232,208,176,0.45);
        font-size: 13.5px;
        gap: 16px;
        transition: background 0.2s;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }

    .info-label {
        color: var(--text-muted);
        font-weight: 300;
        flex-shrink: 0;
        font-size: 13px;
    }

    .info-val {
        color: var(--brown-dark);
        font-weight: 400;
        text-align: right;
    }

    /* Code pill */
    .info-val code {
        font-size: 11.5px;
        background: linear-gradient(135deg, var(--cream-deep), #EDD9BE);
        color: var(--brown-mid);
        padding: 3px 10px;
        border-radius: 6px;
        font-family: 'DM Mono', monospace;
        font-weight: 300;
        border: 1px solid rgba(232,208,176,0.6);
        letter-spacing: 0.3px;
    }

    /* Copy button */
    .copy-btn {
        background: none; border: none; cursor: pointer;
        color: var(--brown-light); opacity: 0.5;
        padding: 2px 4px; margin-left: 4px;
        border-radius: 4px;
        transition: opacity 0.2s, transform 0.15s;
        vertical-align: middle;
        font-size: 12px;
    }
    .copy-btn:hover { opacity: 1; transform: scale(1.1); }
    .copy-btn.copied { color: var(--success-mid); opacity: 1; }

    /* Scan count badge */
    .badge-scan {
        display: inline-flex; align-items: center; gap: 5px;
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: var(--warning-text);
        font-size: 12px; font-weight: 500;
        padding: 4px 12px;
        border-radius: 100px;
        border: 1px solid rgba(245,158,11,0.2);
    }

    /* Animated scan counter */
    .scan-counter {
        font-variant-numeric: tabular-nums;
        display: inline-block;
    }

    /* ═══════════════════════════════════════════════════════
       CRYPTO BLOCK
    ═══════════════════════════════════════════════════════ */
    .crypto-block {
        background: linear-gradient(135deg, var(--cream-deep), rgba(237,217,190,0.4));
        border: 1px solid rgba(232,208,176,0.7);
        border-radius: var(--radius-md);
        padding: 12px 14px;
        width: 100%;
        position: relative;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
        group: true;
    }
    .crypto-block:hover {
        border-color: rgba(201,147,90,0.4);
        box-shadow: 0 0 0 3px rgba(201,147,90,0.06);
    }
    .crypto-block-label {
        font-size: 9.5px; letter-spacing: 1.4px; text-transform: uppercase;
        color: var(--text-muted); font-weight: 500; margin-bottom: 6px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .crypto-text {
        font-size: 10.5px;
        font-family: 'DM Mono', monospace;
        font-weight: 300;
        color: var(--brown-mid);
        word-break: break-all;
        line-height: 1.6;
        letter-spacing: 0.2px;
        user-select: all;
    }
    .crypto-copy-hint {
        font-size: 9px; color: var(--text-muted); opacity: 0;
        transition: opacity 0.2s;
        letter-spacing: 0.5px;
    }
    .crypto-block:hover .crypto-copy-hint { opacity: 1; }

    /* ═══════════════════════════════════════════════════════
       ADMIN LINK
    ═══════════════════════════════════════════════════════ */
    .admin-link {
        text-align: center;
        margin-top: 32px;
    }
    .admin-link a {
        font-size: 11px; font-weight: 400;
        color: var(--brown-light);
        opacity: 0.35;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: opacity 0.2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .admin-link a:hover { opacity: 0.65; }

    /* ═══════════════════════════════════════════════════════
       TIMESTAMP FOOTER
    ═══════════════════════════════════════════════════════ */
    .scan-timestamp {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        font-size: 11px; color: var(--text-muted); opacity: 0.6;
        margin-top: 10px;
        letter-spacing: 0.3px;
    }
    .scan-timestamp::before,
    .scan-timestamp::after {
        content: '';
        height: 1px; width: 30px;
        background: linear-gradient(90deg, transparent, var(--beige-border));
    }
    .scan-timestamp::after {
        background: linear-gradient(90deg, var(--beige-border), transparent);
    }

    /* ═══════════════════════════════════════════════════════
       EXPAND/COLLAPSE CRYPTO
    ═══════════════════════════════════════════════════════ */
    .expand-toggle {
        background: none; border: none; cursor: pointer;
        color: var(--text-muted); font-size: 11px;
        display: flex; align-items: center; gap: 5px;
        padding: 0; letter-spacing: 0.5px;
        transition: color 0.2s;
    }
    .expand-toggle:hover { color: var(--brown); }
    .expand-toggle i { transition: transform 0.3s var(--transition); }
    .expand-toggle.open i { transform: rotate(180deg); }
    .crypto-content {
        overflow: hidden;
        transition: max-height 0.4s var(--transition), opacity 0.3s ease;
        max-height: 0; opacity: 0;
    }
    .crypto-content.open { max-height: 500px; opacity: 1; }

    /* ═══════════════════════════════════════════════════════
       TOAST
    ═══════════════════════════════════════════════════════ */
    .toast-copy {
        position: fixed;
        bottom: 32px; left: 50%; transform: translateX(-50%) translateY(16px);
        background: var(--brown-dark);
        color: var(--cream);
        font-size: 12.5px; font-weight: 400;
        padding: 10px 22px;
        border-radius: 100px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.25s, transform 0.25s var(--transition);
        z-index: 999;
        display: flex; align-items: center; gap: 8px;
        white-space: nowrap;
    }
    .toast-copy.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* ═══════════════════════════════════════════════════════
       PRODUCT ACCENT CHIP
    ═══════════════════════════════════════════════════════ */
    .product-name-chip {
        display: inline-flex; align-items: center;
        background: linear-gradient(135deg, var(--warm-glow), var(--warm-glow2));
        border: 1px solid rgba(201,147,90,0.2);
        border-radius: 100px;
        padding: 3px 12px 3px 3px;
        gap: 6px;
    }
    .product-name-chip-dot {
        width: 18px; height: 18px;
        background: linear-gradient(135deg, var(--brown-light), #E8B07A);
        border-radius: 50%;
        flex-shrink: 0;
    }
    .product-name-chip-text {
        font-size: 12.5px; font-weight: 500;
        color: var(--brown);
    }

    /* ═══════════════════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════════════════ */
    @media (max-width: 480px) {
        .page-wrap { padding: 24px 14px 52px; }
        .result-status-card { padding: 28px 20px 22px; }
        .result-title { font-size: 22px; }
        .card-box { padding: 18px; }
        .topbar { padding: 0 16px; height: 58px; }
    }

    /* ═══════════════════════════════════════════════════════
       LOADING SKELETON (saat data belum ada)
    ═══════════════════════════════════════════════════════ */
    .skeleton {
        background: linear-gradient(90deg, var(--cream-deep) 25%, rgba(237,217,190,0.3) 50%, var(--cream-deep) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
        border-radius: 6px;
        display: inline-block;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ═══════════════════════════════════════════════════════
       RIPPLE
    ═══════════════════════════════════════════════════════ */
    .ripple-host { position: relative; overflow: hidden; }
    .ripple {
        position: absolute; border-radius: 50%;
        background: rgba(201,147,90,0.25);
        transform: scale(0); animation: rippleAnim 0.5s linear;
        pointer-events: none;
    }
    @keyframes rippleAnim {
        to { transform: scale(4); opacity: 0; }
    }
    </style>
</head>
<body>

{{-- ═══════════════════ TOPBAR (logic tidak diubah) ═══════════════════ --}}
<div class="topbar">
    <div class="tb-brand">
        <div class="tb-logo">Z&amp;J</div>
        <div>
            <div class="tb-name">Z&amp;J Cookies</div>
            <div class="tb-sub">Verifikasi Produk</div>
        </div>
    </div>
    <div class="tb-badge">Konsumen</div>
</div>

<div class="page-wrap">

    {{-- ═══════════════════ BLADE LOGIC (100% tidak diubah) ═══════════════════ --}}
    @php
        $cssClass = match($status) {
            'ASLI'       => 'asli',
            'DUPLIKASI'  => 'duplikasi',
            'KADALUARSA' => 'kadaluarsa',
            default      => 'palsu',
        };
        $statusText = match($status) {
            'ASLI'       => 'Produk Asli',
            'DUPLIKASI'  => 'Peringatan Duplikasi',
            'KADALUARSA' => 'Produk Kadaluarsa',
            default      => 'Produk Palsu',
        };
    @endphp

    {{-- ═══════════════════ STATUS CARD ═══════════════════ --}}
    <div class="result-status-card {{ $cssClass }}">

        {{-- Icon — conditional per status (logic tidak diubah) --}}
        <div class="result-icon-wrap">
            @if($status === 'ASLI')
                <svg class="check-svg" viewBox="0 0 52 52" fill="none" width="38" height="38">
                    <circle cx="26" cy="26" r="23" stroke="#22C55E" stroke-width="2.5" fill="none"/>
                    <path d="M15 26.5l8 8 14-14" stroke="#22C55E" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @elseif($status === 'DUPLIKASI')
                <svg viewBox="0 0 24 24" fill="none" width="30" height="30">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="#F59E0B" stroke-width="2" stroke-linejoin="round"/>
                    <line x1="12" y1="9" x2="12" y2="13" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="17" r="0.5" fill="#F59E0B" stroke="#F59E0B" stroke-width="1.5"/>
                </svg>
            @elseif($status === 'KADALUARSA')
                <svg viewBox="0 0 24 24" fill="none" width="30" height="30">
                    <circle cx="12" cy="12" r="10" stroke="#F97316" stroke-width="2"/>
                    <path d="M12 7v5l3.5 3.5" stroke="#F97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @else
                <svg viewBox="0 0 24 24" fill="none" width="30" height="30">
                    <circle cx="12" cy="12" r="10" stroke="#EF4444" stroke-width="2"/>
                    <path d="M15 9l-6 6M9 9l6 6" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
            @endif
        </div>

        <div class="result-title">{{ $statusText }}</div>
        <div class="result-msg">{{ $message }}</div>
    </div>

    {{-- ═══════════════════ DATA PRODUK (logic tidak diubah) ═══════════════════ --}}
    @if($data)
    <div class="card-box">
        <div class="detail-section-label">Detail Produk</div>

        <div class="info-row">
            <span class="info-label">ID Produk</span>
            <span class="info-val">
                <code>{{ $data['product_id'] }}</code>
                <button class="copy-btn ripple-host" onclick="copyText('{{ $data['product_id'] }}', this)" title="Salin">
                    <i class="bi bi-copy"></i>
                </button>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Nama Produk</span>
            <span class="info-val" style="font-weight:500; color:var(--brown);">{{ $data['nama_produk'] }}</span>
        </div>

        @if(isset($data['tanggal_produksi']))
        <div class="info-row">
            <span class="info-label">Tgl. Produksi</span>
            <span class="info-val">{{ $data['tanggal_produksi'] }}</span>
        </div>
        @endif

        <div class="info-row">
            <span class="info-label">Tgl. Kadaluarsa</span>
            <span class="info-val" style="{{ $status === 'KADALUARSA' ? 'color:var(--orange-mid);font-weight:500;' : '' }}">
                @if($status === 'KADALUARSA')
                    <i class="bi bi-exclamation-circle" style="font-size:12px;margin-right:3px;"></i>
                @endif
                {{ $data['tanggal_kadaluarsa'] }}
            </span>
        </div>

        @if(isset($data['batch_number']))
        <div class="info-row">
            <span class="info-label">Batch</span>
            <span class="info-val"><code>{{ $data['batch_number'] }}</code></span>
        </div>
        @endif

        @if(isset($data['scan_count']))
        <div class="info-row">
            <span class="info-label">Total Scan</span>
            <span class="info-val">
                <span class="badge-scan">
                    <i class="bi bi-qr-code-scan" style="font-size:11px;"></i>
                    <span class="scan-counter" data-target="{{ $data['scan_count'] }}">0</span>x
                </span>
            </span>
        </div>
        @endif

        @if(isset($data['first_scan_at']))
        <div class="info-row">
            <span class="info-label">Pertama Scan</span>
            <span class="info-val">{{ $data['first_scan_at'] }}</span>
        </div>
        @endif
    </div>

    {{-- ═══════════════════ CRYPTO CARD (logic tidak diubah) ═══════════════════ --}}
    <div class="card-box">
        <div class="detail-section-label" style="justify-content:space-between;">
            <span>Informasi Kriptografi</span>
            <button class="expand-toggle" id="cryptoToggle" onclick="toggleCrypto()">
                <span>Lihat</span>
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>

        <div class="crypto-content" id="cryptoContent">
            <div style="display:flex; flex-direction:column; gap:10px; padding-top:4px;">

                {{-- SHA-256 --}}
                <div class="crypto-block ripple-host" onclick="copyText('{{ $data['hash_data'] }}', null, 'Hash disalin')">
                    <div class="crypto-block-label">
                        <span>SHA-256 Hash</span>
                        <span class="crypto-copy-hint"><i class="bi bi-copy"></i> Ketuk untuk salin</span>
                    </div>
                    <div class="crypto-text">{{ $data['hash_data'] }}</div>
                </div>

                {{-- ECDSA Signature --}}
                <div class="crypto-block ripple-host" onclick="copyText('{{ $data['signature'] }}', null, 'Signature disalin')">
                    <div class="crypto-block-label">
                        <span>ECDSA Signature</span>
                        <span class="crypto-copy-hint"><i class="bi bi-copy"></i> Ketuk untuk salin</span>
                    </div>
                    <div class="crypto-text">{{ $data['signature'] }}</div>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- Timestamp verifikasi --}}
    <div class="scan-timestamp">
        Diverifikasi pada <span id="scanTime"></span>
    </div>

</div>

{{-- ═══════════════════ ADMIN LINK (logic tidak diubah) ═══════════════════ --}}
<div class="admin-link mb-4">
    <a href="{{ route('admin.login') }}" class="ripple-host">
        <i class="bi bi-shield-lock"></i> Admin Panel
    </a>
</div>

{{-- Toast --}}
<div class="toast-copy" id="toastCopy">
    <i class="bi bi-check2-circle"></i>
    <span id="toastMsg">Disalin!</span>
</div>

<script>
/* ═══════════════════════════════════════════════
   UTILS
═══════════════════════════════════════════════ */
function copyText(text, btn, msg) {
    navigator.clipboard.writeText(text).then(() => {
        showToast(msg || 'Disalin ke clipboard');
        if (btn) {
            btn.classList.add('copied');
            btn.innerHTML = '<i class="bi bi-check2"></i>';
            setTimeout(() => {
                btn.classList.remove('copied');
                btn.innerHTML = '<i class="bi bi-copy"></i>';
            }, 2000);
        }
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = text; document.body.appendChild(ta);
        ta.select(); document.execCommand('copy');
        document.body.removeChild(ta);
        showToast(msg || 'Disalin!');
    });
}

function showToast(msg) {
    const toast = document.getElementById('toastCopy');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2200);
}

/* ═══════════════════════════════════════════════
   CRYPTO EXPAND/COLLAPSE
═══════════════════════════════════════════════ */
function toggleCrypto() {
    const content = document.getElementById('cryptoContent');
    const toggle  = document.getElementById('cryptoToggle');
    const isOpen  = content.classList.contains('open');
    content.classList.toggle('open');
    toggle.classList.toggle('open');
    toggle.querySelector('span').textContent = isOpen ? 'Lihat' : 'Sembunyikan';
}

/* ═══════════════════════════════════════════════
   ANIMATED SCAN COUNTER
═══════════════════════════════════════════════ */
function animateCounters() {
    document.querySelectorAll('.scan-counter[data-target]').forEach(el => {
        const target = parseInt(el.dataset.target, 10);
        if (!target) { el.textContent = target; return; }
        const dur = Math.min(1000, target * 80);
        const start = performance.now();
        function step(now) {
            const p = Math.min((now - start) / dur, 1);
            const ease = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(ease * target);
            if (p < 1) requestAnimationFrame(step);
        }
        setTimeout(() => requestAnimationFrame(step), 500);
    });
}

/* ═══════════════════════════════════════════════
   RIPPLE EFFECT
═══════════════════════════════════════════════ */
document.addEventListener('click', function(e) {
    const host = e.target.closest('.ripple-host');
    if (!host) return;
    const rect = host.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height) * 1.5;
    const ripple = document.createElement('span');
    ripple.className = 'ripple';
    Object.assign(ripple.style, {
        width: size + 'px', height: size + 'px',
        left: (e.clientX - rect.left - size/2) + 'px',
        top:  (e.clientY - rect.top  - size/2) + 'px'
    });
    host.appendChild(ripple);
    ripple.addEventListener('animationend', () => ripple.remove());
});

/* ═══════════════════════════════════════════════
   TIMESTAMP
═══════════════════════════════════════════════ */
function setTimestamp() {
    const now = new Date();
    const opts = { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' };
    document.getElementById('scanTime').textContent = now.toLocaleString('id-ID', opts);
}

/* ═══════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    setTimestamp();
    animateCounters();
});
</script>

</body>
</html>