# test

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).

# Replicate to live server
```
rsync -aAXhv --delete --chown=www-data:www-data \
      --exclude .git/ \
      --exclude certs/ \
      --exclude log/ \
      --exclude node_modules/ \
      --exclude public \
      --exclude src/ \
      --exclude test/ \
      --exclude .gitignore \
      --exclude babel.config.js \
      --exclude jsconfig.json \
      --exclude package.json \
      --exclude package-lock.json \
      --exclude README.md \
      --exclude vue.config.js \
      /home/fwarren/docker/website/wp-www/plugins/nrb_dealers_area \
      nr:/srv/website/wp-www/html/wp-content/plugins

rsync -aAXhv --delete --chown=www-data:www-data \
      /home/fwarren/docker/website/wp-www/plugins/nrb_dealers_area/dist/ \
      nr:/srv/website/wp-www/html/dealers-area/dist/
```

