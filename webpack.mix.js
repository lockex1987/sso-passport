const mix = require('laravel-mix');
const path = require('path');


mix.js('frontend/js/script.js', 'public/js')
    .vue({ version: 2 })
    .sass('frontend/sass/style.scss', 'public/css')
    .sourceMaps(true, 'source-map');

if (mix.inProduction()) {
    mix.version();
}

mix.webpackConfig({
    resolve: {
        alias: {
            // Điều chỉnh để import các file cho dễ
            '~': path.join(__dirname, './frontend/js')
        }
    },

    output: {
        // Các file khác (pages) ở trong thư mục js
        // Thêm hash để không bị cache
        chunkFilename: 'js/[name].js?h=[chunkhash]'
    }
});
