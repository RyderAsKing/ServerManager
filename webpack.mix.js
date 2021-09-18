const mix = require("laravel-mix");

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

mix.js("resources/js/app.js", "public/js")
    .react()
    .sass("resources/sass/app.scss", "public/css")
    .postCss("node_modules/react-toastify/dist/ReactToastify.css", "public/css")
    .postCss("node_modules/xterm/css/xterm.css", "public/css")
    .js("node_modules/xterm/lib/xterm.js", "public/js");
