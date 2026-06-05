/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/views/**/*.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',
    './app/View/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#eff6ff',
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
        itemku: {
          blue: '#08399b',
          'blue-light': '#1e4aa3',
          'blue-dark': '#0e2d6c',
          yellow: '#E09600',
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
          dark: '#0D1421',
          light: '#ffffff'
        },
        muted: '#64748b',
        faint: '#334155',
      },
      fontFamily: {
        sans: ['var(--font-sans)', 'sans-serif'],
        display: ['Oxanium', 'sans-serif'],
        body: ['DM Sans', 'sans-serif'],
      },
      boxShadow: {
        'glow-sm': '0 0 12px rgba(37,99,235,0.35)',
        glow: '0 0 24px rgba(37,99,235,0.45)',
        'glow-lg': '0 0 48px rgba(37,99,235,0.35)',
        'glow-accent': '0 0 20px rgba(249,115,22,0.4)',
        'glow-accent-lg': '0 0 40px rgba(249,115,22,0.35)',
        card: '0 4px 24px rgba(0,0,0,0.4)',
        'card-hover': '0 12px 48px rgba(0,0,0,0.6)',
      },
      animation: {
        'pulse-glow': 'pulseGlow 2.5s ease-in-out infinite',
        shimmer: 'shimmer 1.8s ease-in-out infinite',
        float: 'float 4s ease-in-out infinite',
        'fade-up': 'fadeUp 0.4s ease-out both',
        'fade-in': 'fadeIn 0.25s ease-out both',
        'slide-right': 'slideRight 0.3s ease-out both',
      },
      keyframes: {
        fadeUp: {
          from: { opacity: '0', transform: 'translateY(16px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          from: { opacity: '0', transform: 'translateY(-6px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        slideRight: {
          from: { opacity: '0', transform: 'translateX(-16px)' },
          to: { opacity: '1', transform: 'translateX(0)' },
        },
        pulseGlow: {
          '0%,100%': { opacity: '0.7' },
          '50%': { opacity: '1' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-800px 0' },
          '100%': { backgroundPosition: '800px 0' },
        },
        float: {
          '0%,100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-8px)' },
        },
      },
    },
  },
  plugins: [],
};
