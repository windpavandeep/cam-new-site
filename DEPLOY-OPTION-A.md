# Deploy on cPanel — Option A (Document Root = public)

- **PHP 8.3** (ea-php83). **No .env** — use `config/site.php`.

## 1. Build locally

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

## 2. Upload

Upload the **entire project** into a folder on the server, e.g.:

- `public_html/cam-solution/`  
  or  
- `cam-solution/` in your home directory

**Exclude:** `node_modules/`, `.git/`. **No .env.**

**Include:** `vendor/`, `public/build/`, `public/models/`, `storage/`, `bootstrap/cache/`

## 3. Set Document Root in cPanel

1. **Domains** → your domain → **Manage** or **Document Root**
2. Set to: **`public_html/cam-solution/public`**  
   (or `cam-solution/public` if the project is in your home)

## 4. Configure `config/site.php`

Edit `config/site.php`: set **url** (e.g. `https://yourdomain.com`), **path** (`''` at root), **asset_url**, and **key** (from `php artisan key:generate --show` run locally).

## 5. Permissions and caches (ea-php83)

In the **Laravel root**:

```bash
chmod -R 755 storage bootstrap/cache
php artisan config:cache
php artisan route:cache
```

## 6. PHP

- **Select PHP Version** (or **MultiPHP**) → **ea-php83** (PHP 8.3).

---

**Done.** The site should be live at `https://yourdomain.com`.
