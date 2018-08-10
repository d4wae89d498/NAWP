const webpack = require('webpack');
const path = require('path');

function getPlugin() {
    if(process.env.NODE_ENV === 'production') {
       return [
            new webpack.optimize.UglifyJsPlugin()
        ];
    } else {
        return [
        ];
    }
}

function sourceMap() {
    if (process.env.NODE_ENV !== 'production') {
        return "sourcemap";
    } else {
        return null;
    }
}
config = {
    mode: 'development',
    entry: {
        main: ['./plugins/plugins.ts']
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, './public/')
    },
    resolve: {
        // Add '.ts' and '.tsx' as a resolvable extension.
        extensions:['.ts', '.tsx', '.js'],
        alias: {

        }
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    'style-loader',
                    {
                        loader: 'css-loader',
                        options: { 
                            minimize: true
                        }
                    },
                    'sass-loader?sourceMap'
                ]
            },
            {   
                test: /\.tsx?$/,
                use: 'ts-loader'
            }
        ]
    },
    plugins: getPlugin(),
    devtool: sourceMap()
};

module.exports = config;