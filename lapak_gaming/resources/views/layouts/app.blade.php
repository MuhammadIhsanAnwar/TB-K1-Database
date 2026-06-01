<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Lapak Gaming — Marketplace top-up, item, akun & voucher game terpercaya Indonesia.">
  <link rel="icon" type="image/png" href="{{ url('storage/app/public/logo/logo.png') }}">
  <link rel="apple-touch-icon" href="{{ url('storage/app/public/logo/logo.png') }}">
  <title>@yield('title', config('app.name', 'Lapak Gaming')) — Marketplace Game Terpercaya</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />

  {{-- Tailwind CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>

    const reveals = document.querySelectorAll(
        '.reveal, .reveal-left, .reveal-right'
    );

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.15
    });

    reveals.forEach((el) => observer.observe(el));
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Oxanium', 'sans-serif'],
            body:    ['DM Sans',  'sans-serif'],
          },
          colors: {
            brand: {
              50:  '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              300: '#93c5fd',
              400: '#60a5fa',
              500: '#3b82f6',
              600: '#2563eb',
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a',
              950: '#172554',
            },
            accent: {
              300: '#fdba74',
              400: '#fb923c',
              500: '#f97316',
              600: '#ea580c',
              700: '#c2410c',
            },
            surface: {
              950: '#060A12',
              900: '#090E1A',
              850: '#0D1421',
              800: '#111827',
              750: '#162032',
              700: '#1A2740',
              600: '#1E2D45',
              500: '#243350',
            },
            muted:   '#64748b',
            faint:   '#334155',
          },
          boxShadow: {
            'glow-sm':      '0 0 12px rgba(37,99,235,0.35)',
            'glow':         '0 0 24px rgba(37,99,235,0.45)',
            'glow-lg':      '0 0 48px rgba(37,99,235,0.35)',
            'glow-accent':  '0 0 20px rgba(249,115,22,0.4)',
            'glow-accent-lg':'0 0 40px rgba(249,115,22,0.35)',
            'card':         '0 4px 24px rgba(0,0,0,0.4)',
            'card-hover':   '0 12px 48px rgba(0,0,0,0.6)',
          },
          animation: {
            'pulse-glow':  'pulseGlow 2.5s ease-in-out infinite',
            'shimmer':     'shimmer 1.8s ease-in-out infinite',
            'float':       'float 4s ease-in-out infinite',
            'fade-up':     'fadeUp 0.4s ease-out both',
            'fade-in':     'fadeIn 0.25s ease-out both',
            'slide-right': 'slideRight 0.3s ease-out both',
          },
          keyframes: {
            fadeUp:     { from: { opacity:'0', transform:'translateY(16px)' }, to: { opacity:'1', transform:'translateY(0)' } },
            fadeIn:     { from: { opacity:'0', transform:'translateY(-6px)' }, to: { opacity:'1', transform:'translateY(0)' } },
            slideRight: { from: { opacity:'0', transform:'translateX(-16px)' }, to: { opacity:'1', transform:'translateX(0)' } },
            pulseGlow:  { '0%,100%':{ opacity:'0.7' }, '50%':{ opacity:'1' } },
            shimmer:    { '0%':{ backgroundPosition:'-800px 0' }, '100%':{ backgroundPosition:'800px 0' } },
            float:      { '0%,100%':{ transform:'translateY(0)' }, '50%':{ transform:'translateY(-8px)' } },
          },
        }
      }
    }
  </script>

  <style>
    /* Theme variables for light/dark modes */
    :root{
      --bg: #060A12;
      --surface: #0D1421;
      --text: #e2e8f0;
      --muted: #94a3b8;
      --primary: #2563eb;
      --accent: #f97316;
      --card-border: rgba(255,255,255,0.04);
      --glass-bg: rgba(13,20,33,0.7);
      --btn-primary-bg: #2563eb;
      --input-bg: #090E1A;
      --nav-top-bg: var(--primary);
      --nav-main-bg: var(--primary);
      --nav-cat-bg: #172554;
      /* back-compat names used in some components */
      --color-primary: var(--primary);
      --color-bg-dark: var(--bg);
      --color-surface: var(--surface);
    }

    @media (prefers-color-scheme: light) {
      :root:not([data-theme]) {
        /* Default to light if user prefers light */
        --bg: #f8fafc;
        --surface: #ffffff;
        --text: #0f172a;
        --muted: #64748b;
        --primary: #2563eb;
        --accent: #f97316;
        --card-border: rgba(15,23,42,0.04);
        --glass-bg: rgba(255,255,255,0.7);
        --btn-primary-bg: #2563eb;
        --input-bg: #ffffff;
        --nav-top-bg: var(--primary);
        --nav-main-bg: var(--primary);
        --nav-cat-bg: #60a5fa;
      }
    }

    /* explicit theme attribute overrides */
    [data-theme="dark"]{
      --bg: #060A12;
      --surface: #0D1421;
      --text: #e2e8f0;
      --muted: #94a3b8;
      --primary: #2563eb;
      --accent: #f97316;
      --card-border: rgba(255,255,255,0.04);
      --glass-bg: rgba(13,20,33,0.7);
      --btn-primary-bg: #2563eb;
      --input-bg: #090E1A;
    }

    [data-theme="light"]{
      --bg: #f8fafc;
      --surface: #ffffff;
      --text: #0f172a;
      --muted: #64748b;
      --primary: #2563eb;
      --accent: #f97316;
      --card-border: rgba(15,23,42,0.04);
      --glass-bg: rgba(255,255,255,0.7);
      --btn-primary-bg: #2563eb;
      --input-bg: #ffffff;
    }

    /* ── Reset & Base ──────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--bg);
      color: var(--text);
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
    }
    h1,h2,h3,h4,h5,.font-display { font-family: 'Oxanium', sans-serif; }

    /* Surface utilities to make light/dark switching easier */
    .surface-bg { background: var(--surface); color: var(--text); }
    .surface-panel { background: var(--surface); border: 1px solid var(--card-border); color: var(--text); }
    .surface-text { color: var(--text); }
    .surface-muted { color: var(--muted); }
    .surface-weak { background: rgba(255,255,255,0.03); }
    .surface-panel:hover { filter: brightness(1.03); }

    /* ── Scrollbar ──────────────────────────────────────────────── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #060A12; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #2563eb, #1d4ed8); border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #3b82f6, #2563eb); }

    /* ── Page Grid Background ───────────────────────────────────── */
    .page-bg {
      background-color: var(--bg);
      background-image:
        radial-gradient(ellipse 80% 50% at 20% -10%, rgba(37,99,235,0.06) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 85% 5%,  rgba(249,115,22,0.04) 0%, transparent 55%),
        linear-gradient(rgba(30,45,69,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(30,45,69,0.06) 1px, transparent 1px);
      background-size: 100% 100%, 100% 100%, 48px 48px, 48px 48px;
      background-attachment: fixed;
    }

    /* ── Glassmorphism ──────────────────────────────────────────── */
    .glass {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.04);
    }

    /* ── Global Alert System ─────────────────────────────────── */
    .alert,
    [role="alert"] {
      position: relative;
      overflow: hidden;
      border-radius: 1.25rem;
      padding: 1rem 1.1rem;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 18px 40px rgba(0,0,0,0.22);
      color: #e2e8f0;
    }

    .alert::before,
    [role="alert"]::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.04), transparent 45%, rgba(255,255,255,0.02));
      pointer-events: none;
    }

    .alert > *,
    [role="alert"] > * {
      position: relative;
      z-index: 1;
    }

    .alert-success,
    .alert.alert-success,
    [role="alert"].alert-success {
      border: 1px solid rgba(16,185,129,0.22);
      background: linear-gradient(145deg, rgba(6,78,59,0.85), rgba(6,95,70,0.78));
      box-shadow: 0 18px 40px rgba(6,95,70,0.16);
    }

    .alert-danger,
    .alert-error,
    .alert.alert-danger,
    .alert.alert-error,
    [role="alert"].alert-danger,
    [role="alert"].alert-error {
      border: 1px solid rgba(244,63,94,0.22);
      background: linear-gradient(145deg, rgba(127,29,29,0.9), rgba(136,19,55,0.82));
      box-shadow: 0 18px 40px rgba(136,19,55,0.16);
    }

    .alert-warning,
    .alert.alert-warning,
    [role="alert"].alert-warning {
      border: 1px solid rgba(245,158,11,0.22);
      background: linear-gradient(145deg, rgba(120,53,15,0.88), rgba(146,64,14,0.80));
      box-shadow: 0 18px 40px rgba(146,64,14,0.16);
    }

    .alert-info,
    .alert.alert-info,
    [role="alert"].alert-info {
      border: 1px solid rgba(96,165,250,0.22);
      background: linear-gradient(145deg, rgba(30,41,59,0.88), rgba(15,23,42,0.82));
      box-shadow: 0 18px 40px rgba(37,99,235,0.12);
    }

    .alert .btn-close,
    [role="alert"] .btn-close {
      filter: brightness(1.1) contrast(1.1);
      opacity: 0.82;
    }

    .alert .btn-close:hover,
    [role="alert"] .btn-close:hover {
      opacity: 1;
    }

    .flash-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.75rem;
      height: 2.75rem;
      border-radius: 0.9rem;
      flex-shrink: 0;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
    }

    .flash-title {
      font-size: 0.95rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: 0.01em;
    }

    .flash-text {
      margin-top: 0.25rem;
      color: rgba(226,232,240,0.9);
      line-height: 1.55;
    }

    .page-toast {
      position: fixed;
      right: 1.5rem;
      bottom: 1.5rem;
      z-index: 9999;
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
      max-width: min(24rem, calc(100vw - 2rem));
      padding: 1rem 1.05rem;
      border-radius: 1.1rem;
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 24px 48px rgba(0,0,0,0.28);
      transform-origin: bottom right;
      animation: toastPop 0.22s ease-out both;
    }

    .page-toast__icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.35rem;
      height: 2.35rem;
      border-radius: 0.85rem;
      flex-shrink: 0;
    }

    .page-toast__title {
      font-size: 0.88rem;
      font-weight: 800;
      color: #fff;
      line-height: 1.2;
    }

    .page-toast__text {
      margin-top: 0.2rem;
      font-size: 0.82rem;
      line-height: 1.55;
      color: rgba(226,232,240,0.9);
    }

    .page-toast__close {
      margin-left: 0.3rem;
      align-self: flex-start;
      color: rgba(226,232,240,0.75);
      transition: color 0.2s ease, transform 0.2s ease;
    }

    .page-toast__close:hover {
      color: #fff;
      transform: scale(1.04);
    }

    @keyframes toastPop {
      from { opacity: 0; transform: translateY(10px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .glass-hover:hover {
      background: rgba(22,32,50,0.85);
      border-color: rgba(37,99,235,0.4);
    }

    /* ── Gradient Border Cards ─────────────────────────────────── */
    .card {
      background: var(--surface);
      border: 1px solid var(--card-border);
      border-radius: 12px;
      transition: border-color 0.2s, box-shadow 0.2s, transform 0.18s;
    }
    .card:hover { border-color: rgba(96,165,250,0.08); box-shadow: 0 6px 20px rgba(0,0,0,0.35); }

    .card-glow-border {
      position: relative;
      background: #0D1421;
      border-radius: 16px;
      overflow: hidden;
    }
    .card-glow-border::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 12px;
      padding: 1px;
      background: linear-gradient(135deg, rgba(37,99,235,0.18) 0%, transparent 50%, rgba(249,115,22,0.12) 100%);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
      transition: opacity 0.3s;
    }
    .card-glow-border:hover::before {
      background: linear-gradient(135deg, rgba(37,99,235,0.28) 0%, rgba(59,130,246,0.18) 50%, rgba(249,115,22,0.18) 100%);
    }

    /* ── Product Cards ──────────────────────────────────────────── */
    .product-card {
      background: var(--surface);
      border: 1px solid var(--card-border);
      border-radius: 14px;
      overflow: hidden;
      transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), border-color 0.2s, box-shadow 0.25s;
      cursor: pointer;
    }
    .product-card:hover {
      transform: translateY(-3px) scale(1.01);
      border-color: rgba(37,99,235,0.35);
      box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    }
    /* make product title highlight with primary color on hover */
    .product-card:hover h3 { color: var(--primary); }

    /* ── Buttons ────────────────────────────────────────────────── */
    .btn-primary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.65rem 1.25rem;
      border-radius: 10px;
      font-family: 'Oxanium', sans-serif;
      font-weight: 700;
      font-size: 0.9375rem;
      color: white;
      background: var(--btn-primary-bg); /* solid brand */
      border: 1px solid rgba(37,99,235,0.12);
      transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-primary:hover {
      background: #1d4ed8;
      box-shadow: 0 6px 18px rgba(37,99,235,0.12);
      transform: translateY(-1px);
    }
    .btn-accent {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      font-family: 'Oxanium', sans-serif;
      font-weight: 700;
      font-size: 0.9375rem;
      color: white;
      background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
      border: 1px solid rgba(251,146,60,0.3);
      transition: all 0.2s;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-accent:hover {
      background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
      box-shadow: 0 0 20px rgba(249,115,22,0.45);
      transform: translateY(-1px);
    }
    .btn-ghost {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.9375rem;
      color: #94a3b8;
      background: transparent;
      border: 1px solid #1E2D45;
      transition: all 0.2s;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-ghost:hover { border-color: rgba(37,99,235,0.5); color: white; background: rgba(37,99,235,0.08); }

    /* ── Form Inputs ────────────────────────────────────────────── */
    .input {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--card-border);
      border-radius: 12px;
      padding: 0.8125rem 1rem;
      color: #f1f5f9;
      font-size: 0.9375rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input::placeholder { color: var(--muted); }
    .input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }

    /* ── Badges & Pills ─────────────────────────────────────────── */
    .badge { display:inline-flex; align-items:center; gap:4px; padding:2px 10px; border-radius:99px; font-size:0.7rem; font-weight:700; font-family:'Oxanium',sans-serif; letter-spacing:0.05em; text-transform:uppercase; }
    .badge-blue  { background:rgba(37,99,235,0.15); color:#60a5fa; border:1px solid rgba(37,99,235,0.3); }
    .badge-orange{ background:rgba(249,115,22,0.12); color:#fb923c; border:1px solid rgba(249,115,22,0.25); }
    .badge-green { background:rgba(16,185,129,0.12); color:#34d399; border:1px solid rgba(16,185,129,0.25); }
    .badge-red   { background:rgba(239,68,68,0.12);  color:#f87171; border:1px solid rgba(239,68,68,0.25); }
    .badge-gold  { background:rgba(251,191,36,0.12); color:#fbbf24; border:1px solid rgba(251,191,36,0.25); }

    /* ── Price Text ─────────────────────────────────────────────── */
    .price-text {
      background: linear-gradient(135deg, #60a5fa, #fb923c);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* ── Section title decoration ───────────────────────────────── */
    .section-title { position:relative; padding-left:16px; }
    .section-title::before {
      content:'';
      position:absolute;
      left:0; top:50%;
      transform:translateY(-50%);
      width:4px; height:70%;
      border-radius:4px;
      background:linear-gradient(180deg,#2563eb,#f97316);
    }

    /* ── Skeleton ───────────────────────────────────────────────── */
    .skeleton {
      background:linear-gradient(90deg, #0D1421 25%, #162032 50%, #0D1421 75%);
      background-size:800px 100%;
      animation:shimmer 1.8s infinite;
    }

    /* ── Dropdown & Drawer ──────────────────────────────────────── */
    .dropdown-panel { display:none; animation:fadeIn 0.18s ease-out; }
    .dropdown-panel.open { display:block; }
    #mobile-drawer { transform:translateX(-100%); transition:transform 0.32s cubic-bezier(0.16,1,0.3,1); }
    #mobile-drawer.open { transform:translateX(0); }
    #drawer-overlay { opacity:0; pointer-events:none; transition:opacity 0.3s ease; }
    #drawer-overlay.open { opacity:1; pointer-events:all; }

    /* ── Navbar ─────────────────────────────────────────────────── */
    .navbar-blur { backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px); }
    .nav-active { color:#60a5fa !important; }
    .nav-active::after {
      content:''; display:block; height:2px; border-radius:99px;
      background:linear-gradient(90deg,#2563eb,#f97316); margin-top:3px;
    }

    /* ── Notification / Cart dots ───────────────────────────────── */
    .notif-dot { width:8px;height:8px;background:#f97316;border-radius:50%;border:2px solid #060A12;position:absolute;top:-2px;right:-2px; }

    /* ── Star rating ────────────────────────────────────────────── */
    .star-fill { color:#fbbf24; }

    /* ── Cat scroll ─────────────────────────────────────────────── */
    .cat-scroll { scrollbar-width:none; -ms-overflow-style:none; }
    .cat-scroll::-webkit-scrollbar { display:none; }

    /* ── Ribbon ─────────────────────────────────────────────────── */
    .ribbon {
      position:absolute; top:10px; left:-4px;
      background:linear-gradient(135deg,#f97316,#ea580c);
      color:white; font-size:0.6rem; font-weight:800; letter-spacing:0.08em;
      padding:2px 10px 2px 8px;
      clip-path:polygon(0 0,100% 0,calc(100% - 6px) 50%,100% 100%,0 100%);
      font-family:'Oxanium',sans-serif; text-transform:uppercase;
    }
    .ribbon-blue { background:linear-gradient(135deg,#2563eb,#1d4ed8); }
    .ribbon-accent { background: linear-gradient(135deg,#f97316,#ea580c); }

    /* ── Toggle ─────────────────────────────────────────────────── */
    .toggle-switch { width:40px;height:22px;background:#1E2D45;border-radius:99px;position:relative;cursor:pointer;transition:background 0.25s; }
    .toggle-switch.on { background:#2563eb; }
    .toggle-switch::after { content:'';position:absolute;width:16px;height:16px;background:white;border-radius:50%;top:3px;left:3px;transition:transform 0.25s;box-shadow:0 1px 4px rgba(0,0,0,0.3); }
    .toggle-switch.on::after { transform:translateX(18px); }

    /* ── Status badges ──────────────────────────────────────────── */
    .status-pending   { background:rgba(251,191,36,0.12);color:#fbbf24;border:1px solid rgba(251,191,36,0.25); }
    .status-active    { background:rgba(16,185,129,0.12);color:#34d399;border:1px solid rgba(16,185,129,0.25); }
    .status-completed { background:rgba(37,99,235,0.12);color:#60a5fa;border:1px solid rgba(37,99,235,0.25); }
    .status-cancelled { background:rgba(239,68,68,0.12);color:#f87171;border:1px solid rgba(239,68,68,0.25); }

    /* Legacy itemku utility classes for backward compatibility */
    .text-itemku-blue { color: var(--primary) !important; }
    .bg-itemku-blue { background: var(--primary) !important; color: white !important; }
    .bg-itemku-yellow { background: var(--accent) !important; color: white !important; }
    .border-itemku-blue { border-color: var(--primary) !important; }
    /* Tailwind-like focus utility names include colons in markup; escape in selector */
    .focus\:border-itemku-blue:focus, .focus\:border-itemku-blue:focus-within { border-color: var(--primary) !important; }
    .focus\:ring-itemku-blue:focus, .focus\:ring-itemku-blue:focus-within { box-shadow: 0 0 0 4px rgba(37,99,235,0.12) !important; }
    .hover\:text-itemku-blue:hover { color: var(--primary) !important; }

    /* ── Page load animation ────────────────────────────────────── */
    .animate-delay-1 { animation-delay:0.05s; }
    .animate-delay-2 { animation-delay:0.10s; }
    .animate-delay-3 { animation-delay:0.15s; }
    .animate-delay-4 { animation-delay:0.20s; }
    .animate-delay-5 { animation-delay:0.25s; }

    /* ── Stat cards ─────────────────────────────────────────────── */
    .stat-card {
      background:#0D1421;
      border:1px solid #1E2D45;
      border-radius:16px;
      padding:1.5rem;
      transition:border-color 0.2s, transform 0.2s;
    }
    .stat-card:hover { border-color:rgba(37,99,235,0.35); transform:translateY(-2px); }

    /* ── Custom radio/check ─────────────────────────────────────── */
    .payment-option input[type="radio"] { display:none; }
    .payment-option label {
      display:block; cursor:pointer; border:1px solid #1E2D45; border-radius:12px;
      padding:0.875rem; text-align:center; color:#64748b; background:#090E1A;
      transition:all 0.2s;
    }
    .payment-option input[type="radio"]:checked + label {
      border-color:#2563eb; background:rgba(37,99,235,0.12); color:white;
      box-shadow:0 0 0 2px rgba(37,99,235,0.25);
    }

    /* ── Tables ─────────────────────────────────────────────────── */
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th { text-align:left; padding:0.75rem 1rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#475569; border-bottom:1px solid #1E2D45; font-family:'Oxanium',sans-serif; }
    .data-table td { padding:0.875rem 1rem; border-bottom:1px solid rgba(30,45,69,0.5); font-size:0.9rem; }
    .data-table tr:last-child td { border-bottom:none; }
    .data-table tbody tr:hover { background:rgba(22,32,50,0.5); }

    /* ── Scroll reveal ───────────────────────────────────────────── */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition:
            opacity 0.8s ease,
            transform 0.8s ease;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .reveal-left {
        opacity: 0;
        transform: translateX(-40px);
        transition:
            opacity 0.8s ease,
            transform 0.8s ease;
    }

    .reveal-left.active {
        opacity: 1;
        transform: translateX(0);
    }

    .reveal-right {
        opacity: 0;
        transform: translateX(40px);
        transition:
            opacity 0.8s ease,
            transform 0.8s ease;
    }

    .reveal-right.active {
        opacity: 1;
        transform: translateX(0);
    }

    /* ═══ Premium Navbar Reveal ═══ */
    .reveal-navbar {
      opacity: 0;
      transform: translateY(-20px) scale(0.98);
      animation: navbarReveal 0.8s cubic-bezier(.22,1,.36,1) forwards;
    }

    @keyframes navbarReveal {
      0% {
        opacity: 0;
        transform: translateY(-20px) scale(0.98);
      }

      60% {
        opacity: 1;
        transform: translateY(4px) scale(1);
      }

      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* ═══ PREMIUM NAVBAR UNDERLINE ═══ */

      .nav-link {
        position: relative;
        transition:
          color .35s ease,
          transform .35s ease;
      }

      /* garis bawah */
      .nav-link::after {
        content: "";
        position: absolute;

        left: 14px;
        bottom: -5px;

        width: calc(100% - 28px);
        height: 2.5px;

        border-radius: 999px;

        /* mengikuti warna tema */
        background: linear-gradient(
          90deg,
          #2563eb,
          #3b82f6,
          #60a5fa
        );

        box-shadow:
          0 0 10px rgba(37,99,235,.45),
          0 0 18px rgba(59,130,246,.25);

        transform: scaleX(0) translateY(2px);
        transform-origin: center;

        opacity: 0;

        transition:
          transform .45s cubic-bezier(.22,1,.36,1),
          opacity .35s ease;
      }

      /* menu aktif */
      .nav-link.active::after {
        transform: scaleX(1) translateY(0);
        opacity: 1;
      }

      /* saat hover navbar */
      nav:hover .nav-link.active::after {
        transform: scaleX(.4) translateY(2px);
        opacity: 0;
      }

      /* menu yang disorot */
      .nav-link:hover::after {
        transform: scaleX(1) translateY(0);
        opacity: 1;
      }

      /* sedikit efek hover */
      .nav-link:hover {
        transform: translateY(-1px);
      }

      /* Smooth Reveal Animation */
        .reveal-card{
          opacity: 0;
          transform: translateY(45px);
          transition:
            opacity .7s ease,
            transform .7s cubic-bezier(.22,1,.36,1);

          will-change: opacity, transform;
        }

        .reveal-card.show{
          opacity: 1;
          transform: translateY(0);
        }

        /* Delay */
        .reveal-delay-1 { transition-delay: .05s; }
        .reveal-delay-2 { transition-delay: .1s; }
        .reveal-delay-3 { transition-delay: .15s; }
        .reveal-delay-4 { transition-delay: .2s; }
        .reveal-delay-5 { transition-delay: .25s; }
        .reveal-delay-6 { transition-delay: .3s; }

        /* =========================
            PREMIUM PAGINATION
            ========================= */

          .pagination{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
          }

          .pagination .page-item{
            list-style:none;
          }

          .pagination .page-link{
            min-width:42px;
            height:42px;

            display:flex;
            align-items:center;
            justify-content:center;

            padding:0 14px;

            border-radius:14px;

            background:rgba(17,24,39,.75);

            border:1px solid rgba(59,130,246,.12);

            color:#cbd5e1;

            font-size:.95rem;
            font-weight:600;

            transition:
              transform .25s ease,
              background .25s ease,
              border-color .25s ease,
              color .25s ease,
              box-shadow .25s ease;

            backdrop-filter:blur(10px);
          }

          /* hover */
          .pagination .page-link:hover{
            transform:translateY(-2px);

            background:rgba(37,99,235,.14);

            border-color:rgba(59,130,246,.45);

            color:white;

            box-shadow:
              0 0 0 1px rgba(59,130,246,.15),
              0 8px 20px rgba(37,99,235,.18);
          }

          /* active page */
          .pagination .page-item.active .page-link{
            background:linear-gradient(
              135deg,
              rgba(37,99,235,.95),
              rgba(59,130,246,.9)
            );

            border-color:transparent;

            color:white;

            box-shadow:
              0 10px 24px rgba(37,99,235,.35);
          }

          /* disabled */
          .pagination .page-item.disabled .page-link{
            opacity:.35;
            cursor:not-allowed;
            transform:none;
            box-shadow:none;
          }

          /* next prev */
          .pagination .page-link[rel="next"],
          .pagination .page-link[rel="prev"]{
            padding:0 18px;
            font-size:.9rem;
          }

         /* ═══════════════════════════════════════
              GAMING ANIMATED BACKGROUND
            ═══════════════════════════════════════ */

          #gaming-bg{
              position: fixed;
              inset: 0;
              z-index: -1;
              overflow: hidden;
              background:
                  radial-gradient(circle at top left, rgba(37,99,235,.18), transparent 40%),
                  radial-gradient(circle at bottom right, rgba(249,115,22,.14), transparent 40%),
                  #060A12;
          }
      /* ELEMENT GAMING */
      .gaming-particle{
          position: absolute;
          opacity: .45;
          animation: floatParticle linear infinite;
          filter: drop-shadow(0 0 8px rgba(37,99,235,.35));
      }

      /* garis neon */
      .gaming-particle.line{
          width: 2px;
          height: 80px;
          background: linear-gradient(
              to bottom,
              transparent,
              rgba(37,99,235,.9),
              transparent
          );
          transform: rotate(25deg);
      }

      /* kotak futuristik */
      .gaming-particle.square{
          width: 12px;
          height: 12px;
          border: 1px solid rgba(249,115,22,.7);
          transform: rotate(45deg);
      }

      /* plus cyber */
      .gaming-particle.plus::before,
      .gaming-particle.plus::after{
          content:'';
          position:absolute;
          background:rgba(37,99,235,.85);
      }

      .gaming-particle.plus::before{
          width:14px;
          height:2px;
          top:6px;
      }

      .gaming-particle.plus::after{
          width:2px;
          height:14px;
          left:6px;
      }

      /* glow tambahan */
      .gaming-particle::after{
          content:'';
          position:absolute;
          inset:-8px;
          border-radius:999px;
          background:transparent;
          box-shadow:
              0 0 20px rgba(37,99,235,.15),
              0 0 35px rgba(249,115,22,.08);
      }

      @keyframes floatParticle{

          from{
              transform: translateY(110vh);
              opacity:0;
          }

          10%{
              opacity:.5;
          }

          90%{
              opacity:.5;
          }

          to{
              transform: translateY(-120px);
              opacity:0;
          }
      }
  </style>

  @stack('styles')
</head>

<body class="page-bg text-slate-200 min-h-screen">

<script>
  window.chatInboxUrl = <?php echo json_encode(route('chat.inbox.poll')); ?>;
</script>

@if(request()->routeIs('home') || request()->routeIs('marketplace.home'))
  <div id="page-preloader" class="fixed inset-0 z-[2000] flex items-center justify-center bg-slate-950/95 backdrop-blur-xl transition-opacity duration-500">
    <div class="max-w-xs w-full p-8 rounded-[32px] border border-white/10 bg-slate-950/95 shadow-[0_0_60px_rgba(56,189,248,0.24)] text-center">
      <div class="mx-auto mb-6 w-24 h-24 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 border border-white/10 flex items-center justify-center shadow-glow">
        <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="w-16 h-16 object-contain" />
      </div>
      <div class="text-xl font-bold text-white mb-2">Lapak Gaming</div>
      <p class="text-sm surface-muted mb-6">Sedang memuat konten terbaik untuk kamu.</p>
      <div class="h-2 rounded-full bg-slate-800 overflow-hidden">
        <div id="page-preloader-bar" class="h-full w-0 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 transition-all duration-300"></div>
      </div>
      <div id="page-preloader-status" class="mt-4 text-xs uppercase tracking-[0.22em] text-slate-400">Memuat halaman...</div>
    </div>
  </div>
@endif

<div id="gaming-bg"></div>

  {{-- Ambient top glow --}}
  <div class="pointer-events-none fixed top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-600/60 to-transparent z-50"></div>

  {{-- Mobile Drawer Overlay --}}
  <div id="drawer-overlay" class="fixed inset-0 bg-black/75 z-40 backdrop-blur-sm" onclick="closeDrawer()"></div>

  {{-- Navbar --}}
  @include('components.navbar')

  {{-- Flash Messages --}}
  @if(session('success') || session('error') || session('warning'))
  <div class="max-w-7xl mx-auto px-4 pt-4 animate-fade-up space-y-3">
    @if(session('success'))
      <div class="alert alert-success flex items-start gap-3 text-sm text-slate-100">
        <div class="flash-badge bg-emerald-500/15 text-emerald-200">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <p class="flash-title">Berhasil</p>
          <p class="flash-text">{{ session('success') }}</p>
        </div>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger flex items-start gap-3 text-sm text-slate-100">
        <div class="flash-badge bg-rose-500/15 text-rose-200">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <p class="flash-title">Terjadi kesalahan</p>
          <p class="flash-text">{{ session('error') }}</p>
        </div>
      </div>
    @endif
    @if(session('warning'))
      <div class="alert alert-warning flex items-start gap-3 text-sm text-slate-100">
        <div class="flash-badge bg-amber-500/15 text-amber-200">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
          <p class="flash-title">Peringatan</p>
          <p class="flash-text">{{ session('warning') }}</p>
        </div>
      </div>
    @endif
  </div>
  @endif

  {{-- Validation errors global --}}
  @if($errors->any())
  <div class="max-w-7xl mx-auto px-4 pb-4 animate-fade-up">
    <div class="alert alert-danger rounded-3xl p-4 text-sm text-slate-100">
      <div class="flex items-start gap-3">
        <div class="flash-badge bg-rose-500/15 text-rose-200">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
          <p class="flash-title">Perbaiki data berikut</p>
          <ul class="mt-3 list-disc list-inside space-y-1 text-slate-300">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- Main Content --}}
  <main class="min-h-screen">
    @yield('content')
  </main>

  {{-- Footer --}}
  @unless(request()->routeIs('admin.*'))
    @include('components.footer')
    @include('components.bottom-nav')
  @endunless

  {{-- ═══ CORE SCRIPTS ═══ --}}
  <script>
    // ── Dropdown Manager ──────────────────────────────────
    const dropdowns = ['cat-dropdown','notif-dropdown','cart-dropdown','user-dropdown'];

    function toggleDropdown(id) {
      const target = document.getElementById(id);
      const isOpen = target.classList.contains('open');
      dropdowns.forEach(ddId => {
        const el = document.getElementById(ddId);
        if (el) el.classList.remove('open');
      });
      const arrow = document.getElementById('cat-dropdown-arrow');
      if (arrow) arrow.style.transform = '';
      if (!isOpen) {
        target.classList.add('open');
        if (id === 'cat-dropdown' && arrow) arrow.style.transform = 'rotate(180deg)';
      }
    }

    function escapeHtml(value) {
      return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    async function loadNotificationPreview() {
      const panel = document.getElementById('notif-dropdown');
      const body = document.getElementById('notif-dropdown-body');
      const badge = document.getElementById('notif-badge');

      if (!panel || !body) return;

      const notificationsUrl = panel.dataset.notificationsUrl;
      const notificationReadBaseUrl = panel.dataset.notificationsReadBaseUrl;
      if (!notificationsUrl || !notificationReadBaseUrl) return;

      body.innerHTML = '<div class="px-5 py-8 text-sm text-slate-400 text-center flex flex-col items-center justify-center gap-3"><svg class="w-10 h-10 text-slate-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Memuat notifikasi...</div>';

      try {
        const response = await fetch(notificationsUrl, {
          credentials: 'same-origin',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
        });

        if (!response.ok) throw new Error('Failed to load notifications');

        const data = await response.json();
        const items = Array.isArray(data.items) ? data.items : [];

        if (badge) {
          const count = Number(data.unread_count) || 0;
          badge.textContent = count > 99 ? '99+' : count;
          badge.classList.toggle('hidden', count <= 0);
        }

        if (!items.length) {
          body.innerHTML = '<div class="px-5 py-8 text-sm text-slate-400 text-center flex flex-col items-center justify-center gap-3"><svg class="w-10 h-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>Tidak ada notifikasi baru.</div>';
          return;
        }

        body.innerHTML = items.map((item) => {
          const unread = !item.is_read;
          const title = escapeHtml(item.title || 'Notifikasi');
          const bodyText = escapeHtml(item.body || '');
          const link = escapeHtml(item.link || '');
          const createdAt = item.created_at ? new Date(item.created_at).toLocaleString('id-ID', {
            dateStyle: 'medium',
            timeStyle: 'short',
          }) : '';

          return `
            <div class="group relative px-5 py-4 border-b border-white/5 last:border-b-0 hover:bg-white/5 transition-all duration-300 ${unread ? 'bg-cyan-500/[0.03]' : ''}">
              <div class="flex items-start gap-4">
                <div class="shrink-0 mt-1">
                  ${unread 
                    ? \`<div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                         <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                       </div>\` 
                    : \`<div class="w-10 h-10 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center">
                         <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                       </div>\`
                  }
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-center justify-between gap-2 mb-1">
                    <p class="text-sm font-bold truncate ${unread ? 'text-white' : 'text-slate-300'}">${title}</p>
                    <span class="text-[10px] uppercase tracking-wider font-semibold whitespace-nowrap text-slate-500">${createdAt}</span>
                  </div>
                  <p class="text-xs leading-relaxed text-slate-400 line-clamp-2">${bodyText}</p>
                  
                  <div class="mt-3 flex items-center gap-3">
                    ${link ? \`<button type="button" data-notification-id="\${item.id}" data-notification-link="\${link}" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-400 hover:text-cyan-300 transition-colors">
                      Lihat Detail <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>\` : ''}
                    
                    ${unread ? \`<button type="button" data-notification-id="\${item.id}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors ml-auto">
                      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tandai Dibaca
                    </button>\` : ''}
                  </div>
                </div>
              </div>
            </div>
          `;
        }).join('');

        body.querySelectorAll('[data-notification-id]').forEach((button) => {
          button.addEventListener('click', async () => {
            const notificationId = button.getAttribute('data-notification-id');
            const notificationLink = button.getAttribute('data-notification-link');
            if (!notificationId) return;

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const url = new URL(notificationReadBaseUrl, window.location.origin);
            url.pathname = `${url.pathname.replace(/\/$/, '')}/${notificationId}/read`;

            try {
              await fetch(url.toString(), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                  'X-CSRF-TOKEN': csrf || '',
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                },
              });

              if (notificationLink) {
                window.location.href = notificationLink;
                return;
              }

              await loadNotificationPreview();
            } catch (error) {
              // no-op: preview stays visible
            }
          });
        });
      } catch (error) {
        body.innerHTML = '<div class="px-4 py-3 text-sm text-red-300 text-center">Gagal memuat notifikasi.</div>';
      }
    }

    async function loadChatBadge() {

    const badge = document.getElementById('chat-badge');

    if (!badge) return;

    try {

        const response = await fetch(window.chatInboxUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load chat badge');
        }

        const data = await response.json();

        const unread = Number(data.total_unread) || 0;

        if (unread > 0) {

            badge.textContent = unread > 99
                ? '99+'
                : unread;

            badge.classList.remove('hidden');

        } else {

            badge.classList.add('hidden');
        }

    } catch (error) {

        // no-op

    }
}
    async function markAllNotificationsRead() {
      const panel = document.getElementById('notif-dropdown');
      if (!panel) return;

      const url = panel.dataset.notificationsReadAllUrl;
      if (!url) return;

      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      try {
        const response = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'X-CSRF-TOKEN': csrf || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
        });

        if (!response.ok) throw new Error('Failed to mark all notifications read');

        await loadNotificationPreview();
      } catch (error) {
        // no-op
      }
    }

    document.addEventListener('click', (e) => {
      if (!e.target.closest('[onclick^="toggleDropdown"]')) {
        dropdowns.forEach(ddId => {
          const el = document.getElementById(ddId);
          if (el) el.classList.remove('open');
        });
        const arrow = document.getElementById('cat-dropdown-arrow');
        if (arrow) arrow.style.transform = '';
      }
    });

    dropdowns.forEach(ddId => {
      const el = document.getElementById(ddId);
      if (el) el.addEventListener('click', e => e.stopPropagation());
    });

    // ── Mobile Drawer ──────────────────────────────────
    function openDrawer() {
      document.getElementById('mobile-drawer').classList.add('open');
      document.getElementById('drawer-overlay').classList.add('open');
      document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
      document.getElementById('mobile-drawer').classList.remove('open');
      document.getElementById('drawer-overlay').classList.remove('open');
      document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeDrawer();
        dropdowns.forEach(ddId => {
          const el = document.getElementById(ddId);
          if (el) el.classList.remove('open');
        });
      }
    });

    // ── Toast Notifications ────────────────────────────
    function showToast(msg, type = 'success') {
      const presets = {
        success: {
          shell: 'border-emerald-400/20 bg-emerald-950/95 text-emerald-100',
          icon: 'bg-emerald-500/15 text-emerald-200',
          title: 'Berhasil',
          glyph: '✓',
        },
        error: {
          shell: 'border-rose-400/20 bg-rose-950/95 text-rose-100',
          icon: 'bg-rose-500/15 text-rose-200',
          title: 'Terjadi kesalahan',
          glyph: '!',
        },
        info: {
          shell: 'border-brand-400/20 bg-slate-950/95 text-slate-100',
          icon: 'bg-brand-500/15 text-brand-200',
          title: 'Informasi',
          glyph: 'i',
        },
      };

      const preset = presets[type] || presets.info;
      const toast = document.createElement('div');
      toast.className = `page-toast ${preset.shell}`;
      toast.innerHTML = `
        <div class="page-toast__icon ${preset.icon}">${preset.glyph}</div>
        <div class="min-w-0 flex-1">
          <div class="page-toast__title">${preset.title}</div>
          <div class="page-toast__text">${msg}</div>
        </div>
        <button type="button" class="page-toast__close" aria-label="Tutup notifikasi">✕</button>
      `;

      const closeBtn = toast.querySelector('.page-toast__close');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => toast.remove());
      }

      document.body.appendChild(toast);
      setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateY(10px) scale(0.98)'; toast.style.transition='opacity 0.3s, transform 0.3s'; setTimeout(()=>toast.remove(), 300); }, 3500);
    }

    document.addEventListener("DOMContentLoaded", () => {
    const reveals = document.querySelectorAll(".reveal-card");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, {
        threshold: 0.12
    });

    reveals.forEach((el) => observer.observe(el));

  loadNotificationPreview();
  loadChatBadge();
  setInterval(loadChatBadge, 10000);
  document.addEventListener('visibilitychange', () => {
      if (!document.hidden) {
          loadChatBadge();
      }
  });
});

document.addEventListener('DOMContentLoaded', () => {
    const preloader = document.getElementById('page-preloader');
    const preloaderBar = document.getElementById('page-preloader-bar');
    const preloaderText = document.getElementById('page-preloader-status');

    if (preloader && preloaderBar && preloaderText) {
      let progress = 0;
      const tick = setInterval(() => {
        progress = Math.min(98, progress + Math.random() * 12 + 6);
        preloaderBar.style.width = `${progress}%`;
      }, 250);

      setTimeout(() => {
        clearInterval(tick);
        preloaderBar.style.width = '100%';
        preloaderText.textContent = 'Selesai memuat, selamat datang!';
        preloader.style.opacity = '0';
        setTimeout(() => preloader.remove(), 400);
      }, 3000);
    }

    for(let i = 0; i < 8; i++){

        const particle = document.createElement('span');

        particle.classList.add('gaming-particle');

        const type = types[Math.floor(Math.random() * types.length)];

        particle.classList.add(type);

        particle.style.left = Math.random() * 100 + 'vw';

        particle.style.top = Math.random() * 100 + 'vh';

        particle.style.animationDuration =
            (Math.random() * 10 + 8) + 's';

        particle.style.animationDelay =
            Math.random() * 5 + 's';

        particle.style.opacity = Math.random() * 0.35 + 0.08;

        /* ukuran random */
        const scale = (Math.random() * 0.8 + 0.6);

        particle.style.transform = `scale(${scale}) rotate(${Math.random() * 360}deg)`;

        bg.appendChild(particle);
    }

});
  </script>

  @if(session()->has('alert'))
  <script type="text/x-template">
    window.sessionAlert = {!! json_encode(session('alert')) !!};
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const alertData = window.sessionAlert;
      if (alertData && typeof Swal !== 'undefined') {
        Swal.fire({
          icon: alertData.type || 'info',
          title: alertData.title || '',
          text: alertData.text || '',
          toast: true,
          position: 'top-end',
          timer: 3500,
          showConfirmButton: false,
          timerProgressBar: true,
        });
      }
    });
  </script>
  @endif
  @stack('scripts')
</body>
</html>