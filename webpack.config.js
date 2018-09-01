const path = require('path');
const DESTINATION = path.resolve(__dirname, './public/');

module.exports = {
    entry: {
        'main': ['./bundles/bundles.ts', './src/bundles.ts']
    },
    output: {
        filename: '[name].bundle.js',
        path: DESTINATION
    },
    module: {
        rules: [
            {
                test: [/\.tsx$/, /\.ts$/, /\.js$/],
                use: 'ts-loader',
                exclude: /node_modules/
            },
            {
                test: /\.scss$/,
                use: [
                    "style-loader", // creates style nodes from JS strings
                    "css-loader", // translates CSS into CommonJS
                    "sass-loader" // compiles Sass to CSS, using Node Sass by default
                ],
                exclude: /node_modules/
            }

        ]
    },
    resolve: {
        extensions: [ '.tsx', '.ts', '.js' ]
    },
    devtool: 'inline-source-map',
};


