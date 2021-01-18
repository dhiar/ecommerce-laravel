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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')

mix.browserSync('http://ecommerce-laravel.test');
mix.options({
    extractVueStyles: false,
    processCssUrls: true,
    purifyCss: false,
    uglify: {
      compress: {
        drop_console: true,
      },
      uglifyOptions: {
        compress: false
      },
    },
    terser: {
        minify: (file, sourceMap) => {
            // https://github.com/mishoo/UglifyJS2#minify-options
            const uglifyJsOptions = {
              /* your `uglify-js` package options */
            };
  
            if (sourceMap) {
              uglifyJsOptions.sourceMap = {
                content: sourceMap,
              };
            }
  
            return require("uglify-js").minify(file, uglifyJsOptions);
          },
    },
    clearConsole: false,
    cssNano: {
      discardComments: {removeAll: true},
    }
});
