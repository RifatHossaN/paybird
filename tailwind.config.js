/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './views/**/*.{php,js}',
    './public/**/*.{php,js}',
    './supportChat/**/*.{php,js}',
    './includes/**/*.{php,js}',
    './supportChat/javascript/*.js'
  ],
  options: {
    safelist: [
      'w-5/6',
      'ml-16',
      'w-full',
      'active',
      'bg-blue-100',
      'bg-blue-900',
      'dark:bg-blue-900',
      'dark:bg-gray-700',
      'text-blue-900',
      'dark:text-blue-100',
      'text-gray-900',
      'dark:text-gray-100'
    ],
  },
  theme: {
    extend: {
      colors: {
        colorStatus: {
          Pending: '#FACC15',
          Accepted: '#4ADE80',
          Rejected: '#F87171',
        },
      },
    },
  },
  plugins: [],
}

