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
        itemku: {
          blue: '#08399b',
          'blue-light': '#1e4aa3',
          'blue-dark': '#0e2d6c',
          yellow: '#E09600',
        },
        surface: {
          dark: '#0D1421',
          light: '#ffffff'
        }
      },
      fontFamily: {
        sans: ['var(--font-sans)', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
