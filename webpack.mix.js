let mix = require("laravel-mix");

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

mix.js("js/app.js", "public/js")
.sass("scss/main.scss", "public/css")
.styles(
  [
    "node_modules/slick-carousel/slick/slick.css",
    "node_modules/slick-carousel/slick/slick-theme.css"
  ],
  "public/css/additional.css")
.setPublicPath('public');
