/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./storage/framework/views/*.php",
    "./resources/views/*.blade.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
  ],
  theme: {
    extend: {
      fontSize: {
        '2.5xl': '1.75rem', // Custom size between 2xl and 3xl
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}

