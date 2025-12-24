const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 3 })
   .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve('resources/js'),
            },
        },
   });
