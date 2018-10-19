// production config
const merge = require("webpack-merge");
const {resolve} = require("path");

const commonConfig = require("./common");

module.exports = merge(commonConfig, {
  mode: "production",
    entry: [
        "./src/index.ts" // the entry point of our app
        // "./bundles/index.ts" // the entry point of our bundles
    ],
  output: {
    filename: "generated_js/app.min.js",
    path: resolve(__dirname, "../../public"),
    publicPath: "/",
  },
  devtool: "source-map",
  plugins: [],
});
