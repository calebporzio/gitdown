const mix = require('laravel-mix');

mix.sass('src/sass/styles-light.scss', 'dist/styles-light.css');

mix.sass('src/sass/styles-dark.scss', 'dist/styles-dark.css');