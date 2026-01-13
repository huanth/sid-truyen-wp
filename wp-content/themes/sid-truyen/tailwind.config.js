/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.php"],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: '#3b82f6', // Premium Blue
        secondary: '#8b5cf6', // Violet
        accent: '#f59e0b', // Amber
        dark: {
          bg: '#1a1a1a',
          surface: '#262626',
          text: '#e5e5e5'
        },
        light: {
          bg: '#ffffff',
          surface: '#f3f4f6',
          text: '#1f2937'
        }
      },
      fontFamily: {
        // Use system fonts or link Google Fonts in header.php
        sans: ['Inter', 'sans-serif'],
        reading: ['Merriweather', 'serif'], // Optimized for reading
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
