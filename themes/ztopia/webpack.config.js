var webpack = require("webpack");
var path = require("path");

var config = {
	context: __dirname,
    entry: {
		app: "./assets/src/app.jsx",
	},

    output: {
        path: path.join(__dirname, "/assets/dist"),
        filename: "app.js"
    },

	watch: true,

    module: {
        loaders: [
            {
                test: /\.js$|\.jsx$/,
                loaders: "babel-loader",
                exclude: /node_modules/,
				query: {
			        presets: ["es2015", "react"]
		        }
            }
        ]
    },

    resolve: {
        extensions: [".js", ".jsx"]
    },

	plugins: [
		new webpack.DefinePlugin({
			"process.env": {
				"NODE_ENV": JSON.stringify("production")
			}
		}),
		// Make jQuery accessible to every React component
		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery"
		}),
		new webpack.optimize.UglifyJsPlugin({minimize: true})
	]
};

module.exports = config;
