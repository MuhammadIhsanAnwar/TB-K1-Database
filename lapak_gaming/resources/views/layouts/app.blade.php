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
    /* ── Reset & Base ──────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'DM Sans', sans-serif;
      background-color: #060A12;
      color: #e2e8f0;
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
    }
    h1,h2,h3,h4,h5,.font-display { font-family: 'Oxanium', sans-serif; }

    /* ── Scrollbar ──────────────────────────────────────────────── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #060A12; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #2563eb, #1d4ed8); border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #3b82f6, #2563eb); }

    /* ── Page Grid Background ───────────────────────────────────── */
    .page-bg {
      background-color: #060A12;
      background-image:
        radial-gradient(ellipse 80% 50% at 20% -10%, rgba(37,99,235,0.18) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 85% 5%,  rgba(249,115,22,0.10) 0%, transparent 55%),
        linear-gradient(rgba(30,45,69,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(30,45,69,0.06) 1px, transparent 1px);
      background-size: 100% 100%, 100% 100%, 48px 48px, 48px 48px;
      background-attachment: fixed;
    }

    /* ── Glassmorphism ──────────────────────────────────────────── */
    .glass {
      background: rgba(13,20,33,0.7);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(30,45,69,0.8);
    }
    .glass-hover:hover {
      background: rgba(22,32,50,0.85);
      border-color: rgba(37,99,235,0.4);
    }

    /* ── Gradient Border Cards ─────────────────────────────────── */
    .card {
      background: #0D1421;
      border: 1px solid #1E2D45;
      border-radius: 16px;
      transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .card:hover { border-color: rgba(37,99,235,0.45); box-shadow: 0 8px 32px rgba(0,0,0,0.5); }

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
      border-radius: 16px;
      padding: 1px;
      background: linear-gradient(135deg, rgba(37,99,235,0.5) 0%, transparent 50%, rgba(249,115,22,0.3) 100%);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
      transition: opacity 0.3s;
    }
    .card-glow-border:hover::before {
      background: linear-gradient(135deg, rgba(37,99,235,0.8) 0%, rgba(59,130,246,0.4) 50%, rgba(249,115,22,0.6) 100%);
    }

    /* ── Product Cards ──────────────────────────────────────────── */
    .product-card {
      background: #0D1421;
      border: 1px solid #1E2D45;
      border-radius: 14px;
      overflow: hidden;
      transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), border-color 0.2s, box-shadow 0.25s;
      cursor: pointer;
    }
    .product-card:hover {
      transform: translateY(-5px) scale(1.015);
      border-color: rgba(37,99,235,0.55);
      box-shadow: 0 16px 48px rgba(0,0,0,0.5), 0 0 20px rgba(37,99,235,0.15);
    }

    /* ── Buttons ────────────────────────────────────────────────── */
    .btn-primary {
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
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      border: 1px solid rgba(96,165,250,0.3);
      transition: all 0.2s;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      box-shadow: 0 0 20px rgba(37,99,235,0.5);
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
      background: #090E1A;
      border: 1px solid #1E2D45;
      border-radius: 12px;
      padding: 0.8125rem 1rem;
      color: #f1f5f9;
      font-size: 0.9375rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input::placeholder { color: #475569; }
    .input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }

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
  window.chatInboxUrl = '{!! route('chat.inbox.poll') !!}';
</script>

<div id="gaming-bg"></div>

  {{-- Ambient top glow --}}
  <div class="pointer-events-none fixed top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-600/60 to-transparent z-50"></div>

  {{-- Mobile Drawer Overlay --}}
  <div id="drawer-overlay" class="fixed inset-0 bg-black/75 z-40 backdrop-blur-sm" onclick="closeDrawer()"></div>

  {{-- Navbar --}}
  @include('components.navbar')

  {{-- Flash Messages --}}
  @if(session('success') || session('error') || session('warning'))
  <div class="max-w-7xl mx-auto px-4 pt-4 animate-fade-up">
    @if(session('success'))
      <div class="flex items-center gap-3 bg-emerald-900/30 border border-emerald-700/40 text-emerald-300 px-4 py-3 rounded-xl text-sm mb-3">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="flex items-center gap-3 bg-red-900/30 border border-red-700/40 text-red-300 px-4 py-3 rounded-xl text-sm mb-3">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
      </div>
    @endif
    @if(session('warning'))
      <div class="flex items-center gap-3 bg-amber-900/30 border border-amber-700/40 text-amber-300 px-4 py-3 rounded-xl text-sm mb-3">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ session('warning') }}
      </div>
    @endif
  </div>
  @endif

  {{-- Validation errors global --}}
  @if($errors->any())
  <div class="max-w-7xl mx-auto px-4 pt-4">
    <div class="bg-red-900/20 border border-red-700/30 rounded-xl px-4 py-3 mb-3">
      <ul class="space-y-1">
        @foreach($errors->all() as $error)
          <li class="text-sm text-red-300 flex items-center gap-2">
            <span class="w-1 h-1 rounded-full bg-red-400 shrink-0"></span>{{ $error }}
          </li>
        @endforeach
      </ul>
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

      const url = panel.dataset.notificationsUrl;
      if (!url) return;

      body.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400 text-center">Memuat notifikasi...</div>';

      try {
        const response = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
        });

        if (!response.ok) throw new Error('Failed to load notifications');

        const data = await response.json();
        const items = Array.isArray(data.items) ? data.items : [];

        if (badge) {
          badge.classList.toggle('hidden', !(Number(data.unread_count) > 0));
        }

        if (!items.length) {
          body.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ada notifikasi baru.</div>';
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
            <div class="px-4 py-3 border-b border-slate-800 last:border-b-0 ${unread ? 'bg-slate-900/60' : ''}">
              <div class="flex items-start gap-3">
                <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full ${unread ? 'bg-rose-400' : 'bg-slate-700'}"></span>
                <div class="min-w-0 flex-1">
                  <div class="text-sm font-semibold text-white">${title}</div>
                  <div class="mt-1 text-sm text-slate-400">${bodyText}</div>
                  <div class="mt-2 text-[11px] uppercase tracking-[0.18em] text-slate-500">${createdAt}</div>
                </div>
                <div class="ml-2 flex shrink-0 flex-col items-end gap-2">
                  ${link ? `<button type="button" data-notification-id="${item.id}" data-notification-link="${link}" class="text-xs font-semibold text-brand-300 hover:text-brand-200">Buka</button>` : ''}
                  ${unread ? `<button type="button" data-notification-id="${item.id}" data-notification-link="${link}" class="text-xs font-semibold text-slate-300 hover:text-white">Tandai dibaca</button>` : ''}
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
            const readUrl = `${window.location.origin}/notifications/${notificationId}/read`;

            try {
              await fetch(readUrl, {
                method: 'POST',
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

        if (!response.ok) throw new Error('Failed to load chat badge');

        const data = await response.json();
        badge.classList.toggle('hidden', !(Number(data.total_unread) > 0));
      } catch (error) {
        // no-op: badge stays as is
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
      const colors = { success:'border-emerald-600/40 bg-emerald-900/40 text-emerald-300', error:'border-red-600/40 bg-red-900/40 text-red-300', info:'border-brand-600/40 bg-brand-900/30 text-brand-300' };
      const toast = document.createElement('div');
      toast.className = `fixed bottom-6 right-6 z-[9999] flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium shadow-card animate-fade-in ${colors[type] || colors.info}`;
      toast.innerHTML = `<span>${msg}</span>`;
      document.body.appendChild(toast);
      setTimeout(() => { toast.style.opacity='0'; toast.style.transition='opacity 0.3s'; setTimeout(()=>toast.remove(), 300); }, 3500);
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

    const bg = document.getElementById('gaming-bg');

    const types = [
        'line',
        'square',
        'plus'
    ];

    for(let i = 0; i < 40; i++){

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

        particle.style.opacity =
            Math.random() * 0.6 + 0.15;

        /* ukuran random */
        const scale = (Math.random() * 1.4 + 0.6);

        particle.style.transform =
            `scale(${scale}) rotate(${Math.random() * 360}deg)`;

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