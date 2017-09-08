
const { mix } = require('laravel-mix');

const THEME_PATH = 'resources/assets/frontend/';
const FRONTEND_PATH = 'public/frontend/';
const BACKEND_PATH = 'public/backend/';
const NODE_PATH = 'node_modules/';
const SCSS_PATH = 'resources/assets/sass/';

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

mix.js('resources/assets/js/app.js', FRONTEND_PATH + 'js')
    .js('resources/assets/js/bootstrap.js', FRONTEND_PATH + 'js')
    .copyDirectory(THEME_PATH + 'js', FRONTEND_PATH + 'js')
    .copyDirectory(THEME_PATH + 'img', 'public/images')
    .copyDirectory(THEME_PATH + 'css', FRONTEND_PATH + 'css')
    .copyDirectory(THEME_PATH + 'icons', FRONTEND_PATH + 'icons')
    .sass(THEME_PATH + 'sass/theme-styles.scss', FRONTEND_PATH + 'css/theme.css')
    .sass(THEME_PATH + 'sass/blocks.scss', FRONTEND_PATH + 'css')
    .sass(NODE_PATH + 'font-awesome/scss/font-awesome.scss', FRONTEND_PATH + 'css/font.css')
    .sass(SCSS_PATH + 'app.scss', FRONTEND_PATH + 'css')
    .version();
