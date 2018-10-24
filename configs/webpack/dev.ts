// development config
const merge = require("webpack-merge");
const webpack = require("webpack");
const commonConfig = require("./common");
const {resolve} = require("path");

module.exports = merge(commonConfig, {
  mode: "development",
  entry: [
      "./src/index.ts" // the entry point of our app
     // "./bundles/index.ts" // the entry point of our bundles
  ],
  devServer: {
    hot: true, // enable HMR on the server
  },
  devtool: "cheap-module-eval-source-map",
  output: {
      filename: "js/app.min.js",
      path: resolve(__dirname, "../../public"),
      publicPath: "/",
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin(), // enable HMR globally
    new webpack.NamedModulesPlugin(), // prints more readable module names in the browser console on HMR updates
  ],
});
