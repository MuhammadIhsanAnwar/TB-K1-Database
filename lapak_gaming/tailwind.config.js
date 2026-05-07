import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        extend: {

            // ─── COLOR TOKENS ───────────────────────────────────────────────
            colors: {

                // Background layers
                void:    '#080B10',       // deepest background
                abyss:   '#0C1018',       // main page background
                surface: '#111827',       // card / section bg
                raise:   '#162031',       // elevated card / modal
                border:  '#1E2D40',       // default border
                muted:   '#253447',       // muted/disabled bg
                subtle:  '#2E3F56',       // hover state bg

                // Primary accent — electric blue
                blue: {
                    50:  '#EFF8FF',
                    100: '#DBEFFE',
                    200: '#B0DCFD',
                    300: '#67C1FB',
                    400: '#1DA1F7',
                    500: '#0D8CE8',       // ← main brand blue
                    600: '#0870C2',
                    700: '#0A579B',
                    800: '#0D4778',
                    900: '#0E3B61',
                    950: '#082543',
                    glow: '#0D8CE850',    // glow shadow color
                },

                // Secondary accent — neon orange
                orange: {
                    50:  '#FFF8ED',
                    100: '#FFEFD3',
                    200: '#FFDBA5',
                    300: '#FFC06D',
                    400: '#FF9D3A',
                    500: '#FF7C12',       // ← main brand orange
                    600: '#F05E06',
                    700: '#C74407',
                    800: '#9E360D',
                    900: '#7F2F0E',
                    950: '#451407',
                    glow: '#FF7C1250',
                },

                // Cyan for special highlights
                cyan: {
                    400: '#22D3EE',
                    500: '#06B6D4',
                    glow: '#06B6D430',
                },

                // Status colors
                success: '#10B981',
                warning: '#F59E0B',
                error:   '#EF4444',
                info:    '#3B82F6',

                // Text scale
                text: {
                    primary:   '#F0F6FF',     // headings / important text
                    secondary: '#94A3B8',     // body / supporting text
                    muted:     '#4B6480',     // placeholder / disabled
                    inverse:   '#080B10',     // text on light bg
                },
            },

            // ─── TYPOGRAPHY ─────────────────────────────────────────────────
            fontFamily: {
                sans:    ['Sora', ...defaultTheme.fontFamily.sans],
                display: ['Oxanium', 'Sora', ...defaultTheme.fontFamily.sans],
                mono:    ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },

            fontSize: {
                '2xs': ['0.625rem', { lineHeight: '0.875rem' }],
                xs:    ['0.75rem',  { lineHeight: '1rem' }],
                sm:    ['0.875rem', { lineHeight: '1.375rem' }],
                base:  ['1rem',     { lineHeight: '1.625rem' }],
                lg:    ['1.125rem', { lineHeight: '1.75rem' }],
                xl:    ['1.25rem',  { lineHeight: '1.875rem' }],
                '2xl': ['1.5rem',   { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.375rem' }],
                '4xl': ['2.25rem',  { lineHeight: '2.75rem' }],
                '5xl': ['3rem',     { lineHeight: '1.15' }],
                '6xl': ['3.75rem',  { lineHeight: '1.1' }],
                '7xl': ['4.5rem',   { lineHeight: '1.05' }],
            },

            fontWeight: {
                thin:       '100',
                extralight: '200',
                light:      '300',
                normal:     '400',
                medium:     '500',
                semibold:   '600',
                bold:       '700',
                extrabold:  '800',
                black:      '900',
            },

            // ─── SPACING SCALE ───────────────────────────────────────────────
            // Follows 4px base grid. Custom tokens for common patterns.
            spacing: {
                '4.5':  '1.125rem',
                '13':   '3.25rem',
                '15':   '3.75rem',
                '18':   '4.5rem',
                '22':   '5.5rem',
                '26':   '6.5rem',
                '30':   '7.5rem',
                '34':   '8.5rem',
                '68':   '17rem',
                '76':   '19rem',
                '84':   '21rem',
                '88':   '22rem',
                '92':   '23rem',
                '100':  '25rem',
                '112':  '28rem',
                '128':  '32rem',
                '144':  '36rem',
            },

            // ─── BORDER RADIUS ───────────────────────────────────────────────
            borderRadius: {
                none:  '0',
                sm:    '0.25rem',     // 4px
                DEFAULT:'0.5rem',    // 8px
                md:    '0.625rem',   // 10px
                lg:    '0.75rem',    // 12px  ← cards default
                xl:    '1rem',       // 16px
                '2xl': '1.25rem',    // 20px
                '3xl': '1.5rem',     // 24px
                '4xl': '2rem',       // 32px  ← hero / big elements
                full:  '9999px',     // pills / badges
            },

            // ─── SHADOW SYSTEM ───────────────────────────────────────────────
            boxShadow: {
                // Depth shadows — dark themed
                'sm':   '0 1px 3px 0 rgba(0,0,0,0.4)',
                DEFAULT:'0 2px 8px 0 rgba(0,0,0,0.5)',
                'md':   '0 4px 16px 0 rgba(0,0,0,0.55)',
                'lg':   '0 8px 28px 0 rgba(0,0,0,0.6)',
                'xl':   '0 16px 48px 0 rgba(0,0,0,0.65)',
                '2xl':  '0 24px 64px 0 rgba(0,0,0,0.7)',

                // Glow accents
                'glow-blue':   '0 0 20px 0 rgba(13,140,232,0.35), 0 0 60px 0 rgba(13,140,232,0.15)',
                'glow-orange': '0 0 20px 0 rgba(255,124,18,0.35), 0 0 60px 0 rgba(255,124,18,0.15)',
                'glow-cyan':   '0 0 20px 0 rgba(6,182,212,0.35), 0 0 60px 0 rgba(6,182,212,0.15)',

                // Card hover
                'card':        '0 2px 8px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.04)',
                'card-hover':  '0 8px 32px rgba(0,0,0,0.6), 0 0 0 1px rgba(13,140,232,0.2), inset 0 1px 0 rgba(255,255,255,0.06)',

                // Floating elements
                'float':       '0 20px 60px rgba(0,0,0,0.7)',
                'float-blue':  '0 20px 60px rgba(0,0,0,0.7), 0 0 30px rgba(13,140,232,0.2)',

                // Input focus
                'focus-blue':  '0 0 0 3px rgba(13,140,232,0.3)',
                'focus-orange':'0 0 0 3px rgba(255,124,18,0.3)',

                // Inner
                'inner-glow':  'inset 0 1px 0 rgba(255,255,255,0.05), inset 0 -1px 0 rgba(0,0,0,0.2)',

                // Reset
                'none': 'none',
            },

            // ─── BACKGROUNDS ─────────────────────────────────────────────────
            backgroundImage: {
                // Gradients
                'grad-blue':       'linear-gradient(135deg, #0D8CE8 0%, #0A579B 100%)',
                'grad-orange':     'linear-gradient(135deg, #FF9D3A 0%, #FF7C12 100%)',
                'grad-hero':       'linear-gradient(135deg, #0C1018 0%, #0D2540 50%, #0C1018 100%)',
                'grad-card':       'linear-gradient(145deg, rgba(22,32,49,0.9) 0%, rgba(11,18,26,0.9) 100%)',
                'grad-surface':    'linear-gradient(180deg, #111827 0%, #0C1018 100%)',
                'grad-glow-blue':  'radial-gradient(ellipse at top, rgba(13,140,232,0.15) 0%, transparent 70%)',
                'grad-glow-orange':'radial-gradient(ellipse at top, rgba(255,124,18,0.12) 0%, transparent 70%)',
                'grad-mesh':       'radial-gradient(at 40% 20%, rgba(13,140,232,0.08) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(255,124,18,0.06) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(6,182,212,0.05) 0px, transparent 50%)',

                // Texture overlays
                'noise':           "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E\")",

                // Borders
                'border-blue':     'linear-gradient(90deg, transparent, rgba(13,140,232,0.5), transparent)',
                'border-glow':     'linear-gradient(90deg, transparent, rgba(13,140,232,0.3), rgba(255,124,18,0.3), transparent)',
            },

            // ─── ANIMATIONS ──────────────────────────────────────────────────
            transitionDuration: {
                '0':   '0ms',
                '75':  '75ms',
                '100': '100ms',
                '150': '150ms',
                '200': '200ms',
                '300': '300ms',
                '400': '400ms',
                '500': '500ms',
                '700': '700ms',
                '1000':'1000ms',
            },

            transitionTimingFunction: {
                'spring':  'cubic-bezier(0.34, 1.56, 0.64, 1)',
                'smooth':  'cubic-bezier(0.4, 0, 0.2, 1)',
                'bounce-in': 'cubic-bezier(0.68, -0.55, 0.27, 1.55)',
                'ease-out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },

            keyframes: {
                'fade-in': {
                    '0%':   { opacity: '0', transform: 'translateY(8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-up': {
                    '0%':   { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-down': {
                    '0%':   { opacity: '0', transform: 'translateY(-12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'scale-in': {
                    '0%':   { opacity: '0', transform: 'scale(0.94)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                'slide-left': {
                    '0%':   { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'slide-right': {
                    '0%':   { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'glow-pulse': {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(13,140,232,0.3)' },
                    '50%':      { boxShadow: '0 0 40px rgba(13,140,232,0.6), 0 0 80px rgba(13,140,232,0.2)' },
                },
                'shimmer': {
                    '0%':   { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%':      { transform: 'translateY(-6px)' },
                },
                'badge-pop': {
                    '0%':   { transform: 'scale(0)' },
                    '70%':  { transform: 'scale(1.15)' },
                    '100%': { transform: 'scale(1)' },
                },
                'spin-slow': {
                    from: { transform: 'rotate(0deg)' },
                    to:   { transform: 'rotate(360deg)' },
                },
                'ticker': {
                    '0%':   { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
            },

            animation: {
                'fade-in':    'fade-in 0.3s ease-out-expo both',
                'fade-up':    'fade-up 0.4s ease-out-expo both',
                'fade-down':  'fade-down 0.3s ease-out-expo both',
                'scale-in':   'scale-in 0.25s spring both',
                'slide-left': 'slide-left 0.35s ease-out-expo both',
                'slide-right':'slide-right 0.35s ease-out-expo both',
                'glow-pulse': 'glow-pulse 2.5s ease-in-out infinite',
                'shimmer':    'shimmer 2s linear infinite',
                'float':      'float 3s ease-in-out infinite',
                'badge-pop':  'badge-pop 0.3s spring both',
                'spin-slow':  'spin-slow 8s linear infinite',
                'ticker':     'ticker 20s linear infinite',
            },

            // ─── LAYOUT ──────────────────────────────────────────────────────
            screens: {
                'xs': '390px',
                'sm': '640px',
                'md': '768px',
                'lg': '1024px',
                'xl': '1280px',
                '2xl':'1440px',
                '3xl':'1680px',
            },

            maxWidth: {
                'site':  '1440px',
                'prose': '68ch',
            },

            // ─── Z-INDEX ──────────────────────────────────────────────────────
            zIndex: {
                behind:   '-1',
                base:     '0',
                raised:   '10',
                dropdown: '100',
                sticky:   '200',
                overlay:  '300',
                modal:    '400',
                toast:    '500',
                tooltip:  '600',
            },

            // ─── BLUR ────────────────────────────────────────────────────────
            blur: {
                xs:  '2px',
                sm:  '4px',
                DEFAULT: '8px',
                md:  '12px',
                lg:  '16px',
                xl:  '24px',
                '2xl':'40px',
                '3xl':'64px',
            },

            // ─── BACKDROP BLUR ───────────────────────────────────────────────
            backdropBlur: {
                xs:  '2px',
                sm:  '4px',
                DEFAULT: '8px',
                md:  '12px',
                lg:  '16px',
                xl:  '24px',
                '2xl':'40px',
            },
        },
    },

    plugins: [
        forms,
    ],
};