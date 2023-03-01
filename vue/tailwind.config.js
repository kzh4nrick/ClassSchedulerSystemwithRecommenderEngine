const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
    screens: {
      'xs': '475px',
      'md2': '1015px',
      ...defaultTheme.screens,
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}
