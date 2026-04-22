const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
   ])
   .version()
   .browserSync('http://localhost:8000');

if (mix.inProduction()) {
    mix.minify(['public/js/app.js', 'public/css/app.css']);
}