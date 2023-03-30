const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        'roboto': ['Roboto', 'arial', 'sans-serif']
      }
    },
    screens: {
      'xs': '475px',
      'md2': '1015px',
      'lg2': '1189px',
      ...defaultTheme.screens,
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}
