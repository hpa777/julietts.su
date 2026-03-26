const mix = require('laravel-mix')
require('mix-html-builder')
require('laravel-mix-clean')
require('laravel-mix-svg-sprite')
require('mix-tailwindcss')
require('laravel-mix-webp')
/*
const { createProxyMiddleware } = require('http-proxy-middleware')
const apiProxy = createProxyMiddleware('/api', {
    target: 'http://julietts.su/',
    changeOrigin: true,
    logger: console,
})
*/
const buildPath = './app'
const srcPath = './src'

mix.setPublicPath(buildPath)
    .html({
        htmlRoot: srcPath + '/*.html', // Your html root file(s)
        output: '', // The html output folder
        partialRoot: srcPath + '/components', // default partial path
        layoutRoot: srcPath + '/layouts', // default partial path
        inject: false,
        minify: {
            removeComments: true,
        },
    })

    .ImageWebp({
        disable: false,
        from: srcPath + '/images',
        to: srcPath + '/images/webp',
        imageminWebpOptions: {
            quality: 80,
        },
    })

    .svgSprite(
        srcPath + '/images/icons', // The directory containing your SVG files
        '/images/sprite.svg', // The output path for the sprite
        undefined,
        {
            pluginOptions: {
                plainSprite: true,
                spriteAttrs: {
                    id: 'my-custom-sprite-id',
                },
            },
        }
    )

    .copyDirectory(srcPath + '/assets', buildPath + '/assets')
    //.copyDirectory(srcPath + '/scss/fonts', buildPath + '/fonts')
    //	.copyDirectory(buildPath + '/images', './images')
    /*
	.copyDirectory('node_modules/slick-carousel/slick/fonts', buildPath + '/css/fonts')
	.copy('node_modules/slick-carousel/slick/ajax-loader.gif', buildPath + '/css/ajax-loader.gif')	
	*/

    .options({
        processCssUrls: false,
    })
    .js(srcPath + '/js/app.js', 'js/app.js')
    .vue({ version: 3 })
    .webpackConfig((webpack) => {
        return {
            plugins: [
                new webpack.DefinePlugin({
                    __VUE_OPTIONS_API__: true,
                    __VUE_PROD_DEVTOOLS__: false,
                    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: true,
                }),
            ],
        }
    })
    //.sass(srcPath + '/scss/app.scss', '/css/app.css', {}, [require('tailwindcss')])
    /*
    .autoload({
        jquery: ['$', 'window.jQuery', 'jQuery', 'window.$', 'jquery', 'window.jquery'],
    })
    */
    //.extract(['vue', 'inputmask'])

    .tailwind()
    .clean()

if (!mix.inProduction()) {
    mix.browserSync({
        watch: true,
        server: {
            baseDir: buildPath,
            //            middleware: [apiProxy],
        },
        files: [buildPath + '/css/!*.css', buildPath + '/js/!*.js', buildPath + '/!*.html'],
    })
        .sourceMaps()
        .webpackConfig({
            devtool: 'source-map',
        })
}
