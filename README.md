# kitakuclub

```bash
docker compose run --rm -w /var/www/www-data/kitakuclub php-cli chown www-data:www-data -R bootstrap/cache storage
```

```bash
docker compose run --rm -w /var/www/www-data/kitakuclub node sh -c "npm install && npm run build"
```
