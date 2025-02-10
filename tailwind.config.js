import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import primeUiPlugin from 'tailwindcss-primeui';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
      fontSize: {
        '2.5xl': '1.75rem', // Custom size between 2xl and 3xl
      },
      width: {
        'half-minus-2': 'calc(50% - 0.5rem)',
      }
    },
  },
  plugins: [
    forms,
    primeUiPlugin,
  ],
};

