const { EnvironmentPlugin } = require('webpack');
const mix = require('laravel-mix');
const glob = require('glob');
const path = require('path');
const APP_EDITION = (process.env.APP_EDITION || "billonthego").toLowerCase();
// const exec = require('child_process').exec;

/*
 |--------------------------------------------------------------------------
 | Configure mix
 |--------------------------------------------------------------------------
 */

mix.options({
    resourceRoot: process.env.ASSET_URL || undefined,
    processCssUrls: false,
    postCss: [require('autoprefixer')]
});

/*
 |--------------------------------------------------------------------------
 | Configure Webpack
 |--------------------------------------------------------------------------
 */

mix.webpackConfig({
    output: {
        publicPath: process.env.ASSET_URL || undefined,
        libraryTarget: 'window'
    },
    plugins: [
        new EnvironmentPlugin({
            // Application's public url
            BASE_URL: process.env.ASSET_URL ? `${process.env.ASSET_URL}/` : '/'
        })
    ],
    module: {
        rules: [
            {
                test: /\.es6$|\.js$/,
                include: [
                    path.join(__dirname, 'node_modules/bootstrap/'),
                    path.join(__dirname, 'node_modules/popper.js/'),
                    path.join(__dirname, 'node_modules/shepherd.js/')
                ],
                loader: 'babel-loader',
                options: {
                    presets: [['@babel/preset-env', { targets: 'last 2 versions, ie >= 10' }]],
                    plugins: [
                        '@babel/plugin-transform-destructuring',
                        '@babel/plugin-proposal-object-rest-spread',
                        '@babel/plugin-transform-template-literals'
                    ],
                    babelrc: false
                }
            }
        ]
    },
    externals: {
        jquery: 'jQuery',
        moment: 'moment',
        'datatables.net': '$.fn.dataTable',
        jsdom: 'jsdom',
        velocity: 'Velocity',
        hammer: 'Hammer',
        pace: '"pace-progress"',
        chartist: 'Chartist',
        'popper.js': 'Popper',

        // blueimp-gallery plugin
        './blueimp-helper': 'jQuery',
        './blueimp-gallery': 'blueimpGallery',
        './blueimp-gallery-video': 'blueimpGallery'
    }
});

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */

function mixAssetsDir(query, cb) {
    (glob.sync('resources/assets/' + query) || []).forEach(f => {
        f = f.replace(/[\\\/]+/g, '/');
        cb(f, f.replace('resources/assets/', 'public/assets/'));
    });
}

/*
 |--------------------------------------------------------------------------
 | Configure sass
 |--------------------------------------------------------------------------
 */

const sassOptions = {
    precision: 5
};

// Core stylesheets
mixAssetsDir('vendor/scss/**/!(_)*.scss', (src, dest) =>
    mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
);

// Core javascripts
mixAssetsDir('vendor/js/**/*.js', (src, dest) => mix.js(src, dest));

// Libs
mixAssetsDir('vendor/libs/**/*.js', (src, dest) => mix.js(src, dest));
mixAssetsDir('vendor/libs/**/!(_)*.scss', (src, dest) =>
    mix.sass(src, dest.replace(/\.scss$/, '.css'), { sassOptions })
);
mixAssetsDir('vendor/libs/**/*.{png,jpg,jpeg,gif,svg}', (src, dest) => mix.copy(src, dest));
// Copy task for form validation plugin as premium plugin don't have npm package
mixAssetsDir('vendor/libs/formvalidation/dist', (src, dest) => mix.copyDirectory(src, dest));

// Fonts
mixAssetsDir('vendor/fonts/*/*', (src, dest) => mix.copy(src, dest));
mixAssetsDir('vendor/fonts/!(_)*.scss', (src, dest) =>
    mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
);

/*
 |--------------------------------------------------------------------------
 | Application assets
 |--------------------------------------------------------------------------
 */

mixAssetsDir(APP_EDITION + '/js/**/*.js', (src, dest) => {
    // Exclude app edition for public folder.
    dest = dest.replace(APP_EDITION + '/', '');

    mix.scripts(src, dest);
});
mixAssetsDir(APP_EDITION + '/css/**/*.css', (src, dest) => {
    // Exclude app edition for public folder.
    dest = dest.replace(APP_EDITION + '/', '');

    mix.copy(src, dest);
});
mixAssetsDir(APP_EDITION + '/images/**/*.{png,svg,jpg,jpeg,gif}',  (src, dest) => {
    // Exclude app edition for public folder.
    dest = dest.replace(APP_EDITION + '/', '');

    mix.copy(src, dest);
});

// Frontend.
mix.copyDirectory('resources/frontend', 'public/frontend');

// New theme flatpickr for date and datetime picker.
mix.copyDirectory('node_modules/flatpickr', 'public/assets/vendor/libs/flatpickr');

// Alpine.js.
mix.copyDirectory('node_modules/alpinejs', 'public/assets/vendor/libs/alpinejs');

// jQuery mask plugin.
mix.copyDirectory('node_modules/jquery-mask-plugin', 'public/assets/vendor/libs/jquery-mask-plugin');

// Add jqBootstrapValidation.js from resources
mix.js('resources/vendors/js/forms/validation/jqBootstrapValidation.js', 'public/assets/js');

// Add @simonwep/pickr node module.
mix.copyDirectory('node_modules/@simonwep/pickr', 'public/assets/vendor/libs/simonwep');

// Icons | Fonts
mix.copy('node_modules/boxicons/fonts/*', 'public/assets/vendor/fonts/boxicons');
mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts/*', 'public/assets/vendor/fonts/fontawesome');

// App js
mix.js('resources/js/app.js', 'public/js');

mix.version();
