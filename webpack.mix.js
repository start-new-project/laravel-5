const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


/**
 * Admin Assets
 */
   mix.js('resources/assets/admin/js/app.js','public/assets/admin/js')
      .sass('resources/assets/admin/sass/app.scss','public/assets/admin/css')

/**
 * App Assets
 */
   mix.js('resources/assets/app/js/app.js','public/assets/app/js')
      .sass('resources/assets/app/sass/app.scss','public/assets/app/css')
