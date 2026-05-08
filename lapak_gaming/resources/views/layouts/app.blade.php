<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name', 'Lapak Gaming')) — Marketplace Game Terpercaya</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />

  {{-- Tailwind CDN (swap for Vite + tailwind.config.js in production) --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Oxanium', 'sans-serif'],
            body: ['DM Sans', 'sans-serif'],
          },
         colors: {
                gray: {
                  950: '#0a0a0f',
                  925: '#0f172a',
                  900: '#111827',
                  850: '#1f2937',
                  800: '#1e293b',
                  750: '#334155',
                  700: '#475569',
                  600: '#64748b',
                },

                /* GANTI UNGU → BIRU */
                purple: {
                  950: '#0c1a3a',
                  900: '#1e3a8a',
                  800: '#1e40af',
                  700: '#1d4ed8',
                  600: '#2563eb',
                  500: '#3b82f6',
                  400: '#60a5fa',
                  300: '#93c5fd',
                },

                /* VIOLET JUGA JADI BIRU */
                violet: {
                  500: '#3b82f6',
                  400: '#60a5fa'
                },

                /* FUCHSIA → ORANGE */
                fuchsia: {
                  500: '#f97316',
                  400: '#fb923c'
                },
              }
          boxShadow: {
            'glow-sm':     '0 0 12px rgba(124,58,237,0.4)',
            boxShadow: {
            'glow': '0 0 24px rgba(37,99,235,0.5)',
            'glow-lg': '0 0 40px rgba(249,115,22,0.4)',
          }
            'glow-fuchsia':'0 0 20px rgba(217,70,239,0.4)',
          },
          animation: {
            'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
            'shimmer':    'shimmer 1.8s ease-in-out infinite',
            'float':      'float 3s ease-in-out infinite',
            'fade-in':    'fadeIn 0.2s ease-out',
          },
          keyframes: {
            fadeIn:    { from: { opacity:'0', transform:'translateY(-8px)' }, to: { opacity:'1', transform:'translateY(0)' } },
            pulseGlow: { '0%,100%':{ boxShadow:'0 0 12px rgba(124,58,237,0.4)' }, '50%':{ boxShadow:'0 0 28px rgba(124,58,237,0.8)' } },
            shimmer:   { '0%':{ backgroundPosition:'-600px 0' }, '100%':{ backgroundPosition:'600px 0' } },
            float:     { '0%,100%':{ transform:'translateY(0)' }, '50%':{ transform:'translateY(-6px)' } },
          },
        }
      }
    }
  </script>

  <style>
    * { box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; background-color: #0a0a0f; }
    h1,h2,h3,h4,h5,.font-display { font-family: 'Oxanium', sans-serif; }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 8px; height: 8px; }

    ::-webkit-scrollbar-track { 
     background: #020617; }

    ::-webkit-scrollbar-thumb { 
     background: linear-gradient(180deg, #06b6d4, #0891b2);
    border-radius: 99px; 
      }

    ::-webkit-scrollbar-thumb:hover { 
     background: linear-gradient(180deg, #22d3ee, #0e7490);
      }

      html {
      scroll-behavior: smooth;
      }

    /* Grid bg texture */
    .bg-grid {
      background-image: linear-gradient(rgba(37,99,235,0.05) 1px, transparent 1px),
                  linear-gradient(90deg, rgba(249,115,22,0.05) 1px, transparent 1px);
      background-size: 40px 40px;
    }

    /* Gradient border card */
    .card-glow { position: relative; }
    .card-glow::before {
      content: ''; position: absolute; inset: 0; border-radius: 0.75rem;
      padding: 1px;
      background: linear-gradient(135deg, rgba(124,58,237,0.3) 0%, transparent 50%, rgba(217,70,239,0.2) 100%);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
    }
    .card-glow:hover::before {
      background: linear-gradient(135deg, rgba(124,58,237,0.7) 0%, rgba(139,92,246,0.4) 50%, rgba(217,70,239,0.5) 100%);
    }

    /* Shimmer skeleton */
    .skeleton {
      background: linear-gradient(90deg, #1e1e30 25%, #252540 50%, #1e1e30 75%);
      background-size: 600px 100%; animation: shimmer 1.8s infinite;
    }

    /* Dropdown */
    .dropdown-panel { display: none; animation: fadeIn 0.18s ease-out; }
    .dropdown-panel.open { display: block; }

    /* Mobile drawer */
    #mobile-drawer { transform: translateX(-100%); transition: transform 0.32s cubic-bezier(0.16,1,0.3,1); }
    #mobile-drawer.open { transform: translateX(0); }
    #drawer-overlay { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
    #drawer-overlay.open { opacity: 1; pointer-events: all; }

    /* Navbar blur */
    .navbar-blur {
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
}

    /* Active nav link */
    .nav-link-active { color: #a78bfa; }
    .nav-link-active::after { content:''; display:block; height:2px; border-radius:99px; background:linear-gradient(90deg,#7c3aed,#d946ef); margin-top:2px; }

    /* Category pill active */
    .cat-pill-active { background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; box-shadow:0 0 16px rgba(124,58,237,0.5); }

    /* Product card hover */
    .product-card { transition: transform 0.22s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.22s ease; }
    .product-card:hover { transform: translateY(-4px) scale(1.015); box-shadow: 0 12px 40px rgba(124,58,237,0.35); }

    /* Badge pulse */
    .badge-live { animation: pulseGlow 2s ease-in-out infinite; }

    /* Price gradient text */
    .price-text { background:linear-gradient(135deg,#a78bfa,#d946ef); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

    /* Search focus glow */
    #search-input:focus { box-shadow: 0 0 0 2px rgba(124,58,237,0.5), 0 0 20px rgba(124,58,237,0.2); }

    /* Scroll-hide scrollbar for cat nav */
    .cat-scroll { scrollbar-width: none; -ms-overflow-style: none; }
    .cat-scroll::-webkit-scrollbar { display: none; }

    /* Notification dot */
    .notif-dot { width:8px; height:8px; background:#d946ef; border-radius:50%; border:2px solid #0a0a0f; position:absolute; top:-1px; right:-1px; }

    /* Star rating */
    .star-fill { color: #fbbf24; }

    /* Ribbon badge */
    .ribbon {
      position:absolute; top:12px; left:-4px;
      background:linear-gradient(135deg,#d946ef,#7c3aed);
      color:white; font-size:0.65rem; font-weight:700; letter-spacing:0.05em;
      padding:2px 10px 2px 8px;
      clip-path:polygon(0 0,100% 0,calc(100% - 6px) 50%,100% 100%,0 100%);
      font-family:'Oxanium',sans-serif; text-transform:uppercase;
    }

    /* Toggle switch */
    .toggle-switch { width:36px; height:20px; background:#252540; border-radius:99px; position:relative; cursor:pointer; transition:background 0.2s; }
    .toggle-switch.on { background:#7c3aed; }
    .toggle-switch::after { content:''; position:absolute; width:14px; height:14px; background:white; border-radius:50%; top:3px; left:3px; transition:transform 0.2s; }
    .toggle-switch.on::after { transform:translateX(16px); }

    footer {
    margin-top: 0 !important;
    border-top: none !important;
    box-shadow: none !important;
    background: transparent !important;
}
  </style>

  @stack('styles')
</head>

<body class="@yield('body-class', 'bg-gray-950 text-white min-h-screen bg-grid')">
<div class="fixed inset-0 -z-10 bg-gradient-to-b from-blue-900 via-gray-900 to-orange-900"></div>

  {{-- Mobile Drawer Overlay --}}
  <div id="drawer-overlay" class="fixed inset-0 bg-black/70 z-40 backdrop-blur-sm" onclick="closeDrawer()"></div>

  {{-- Components --}}
  @include('components.navbar')

  {{-- Page Content --}}
  @yield('content')

  {{-- Footer --}}
  <div class="-mt-1 border-0 shadow-none">
    @include('components.footer')
</div>

  {{-- ═══ CORE JAVASCRIPT ═══ --}}
  <script>
    // ── Dropdown Manager ──────────────────────────────────
    const dropdowns = ['cat-dropdown', 'notif-dropdown', 'cart-dropdown', 'user-dropdown'];

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
        if (id === 'cat-dropdown' && arrow) {
          arrow.style.transform = 'rotate(180deg)';
        }
      }
    }

    // Close dropdowns on outside click
    document.addEventListener('click', (e) => {
      const isToggle = e.target.closest('[onclick^="toggleDropdown"]');
      if (!isToggle) {
        dropdowns.forEach(ddId => {
          const el = document.getElementById(ddId);
          if (el) el.classList.remove('open');
        });
        const arrow = document.getElementById('cat-dropdown-arrow');
        if (arrow) arrow.style.transform = '';
      }
    });

    // Stop dropdown-content clicks from bubbling up and closing the panel
    dropdowns.forEach(ddId => {
      const el = document.getElementById(ddId);
      if (el) el.addEventListener('click', e => e.stopPropagation());
    });

    // ── Mobile Drawer ─────────────────────────────────────
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

    // ── Category Pills ────────────────────────────────────
    function setActiveCat(btn) {
      document.querySelectorAll('.cat-pill').forEach(p => {
        p.classList.remove('cat-pill-active', 'text-white');
        p.classList.add('text-gray-400', 'border', );
      });
      btn.classList.add('cat-pill-active');
      btn.classList.remove('text-gray-400', 'border',);
    }
  </script>

  {{-- Page-level scripts injected here --}}
  @stack('scripts')

</body>
</html>