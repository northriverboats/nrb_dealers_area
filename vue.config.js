// vue.config.js
const fs = require("fs");

module.exports = {
  publicPath: '/dealers-area/',
  devServer: {
    host: '0.0.0.0',
    headers: {
      'Access-Control-Allow-Origin': 'https://10.10.200.88:8080',
    },
    disableHostCheck: true,
    public: 'www.northriverboats.com:8080',
    https: {
      key: fs.readFileSync("./certs/localhost-key.pem"),
      cert: fs.readFileSync("./certs/localhost.pem")
    },
  }
}
