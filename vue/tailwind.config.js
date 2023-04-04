const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        'roboto': ['Roboto', 'arial', 'sans-serif'],
        'bebas': ['Bebas Neue', 'cursive'],
        'anton': ['Anton', 'sans-serif'],
        'raleway': ['Raleway', 'sans-serif']
      },
      screens: {
        'md2': '1015px',
        'lg2': '1247px',
        'xl2': '1318px'
      },
    },
    screens: {
      'xs': '475px',
      
      ...defaultTheme.screens,
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}
