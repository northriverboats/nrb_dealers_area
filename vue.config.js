// vue.config.js
const fs = require("fs");

module.exports = {
  publicPath: '/dealers-area/',
  devServer: {
    host: '0.0.0.0',
    port: 8090,
    headers: {
      'Access-Control-Allow-Origin': 'https://10.10.200.225:8090',
    },
    disableHostCheck: true,
    public: 'www.northriverboats.com:8090',
    https: {
      key: fs.readFileSync("./certs/localhost-key.pem"),
      cert: fs.readFileSync("./certs/localhost.pem")
    },
  }
}
