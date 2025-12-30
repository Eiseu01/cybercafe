/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        brand: {
          dark: '#213448',
          primary: '#547792',
          secondary: '#94B4C1',
          accent: '#EAE0CF'
        }
      }
    },
  },
  plugins: [],
}
