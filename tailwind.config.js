const { scopedPreflightStyles, isolateInsideOfContainer } = require('tailwindcss-scoped-preflight')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./src/**/*.html', './src/**/*.{js,jsx,ts,tsx,vue}'],
    theme: {
        extend: {
            fontFamily: {
                roboto: ['Roboto', 'sans-serif'],
            },
            boxShadow: {
                head: '0 4px 40px 0 rgba(0, 0, 0, 0.1)',
            },
            colors: {
                mred: '#f7927b',
                mgray: '#666',
                mscrl: '#a4a1a1',
                lgray: '#d9d9d9',
            },
        },
    },
    important: '#jul-constuct',
    plugins: [
        require('tailwind-scrollbar')({ nocompatible: true }),
        scopedPreflightStyles({
            isolationStrategy: isolateInsideOfContainer('#jul-constuct'),
        }),
    ],
}
