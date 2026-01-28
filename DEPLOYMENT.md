# Build and Deploy on cPanel

## 1. Build the project (on your computer)

Run these in the project folder:

```bash
# Install PHP dependencies (production only)
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets (CSS/JS)
npm ci
npm run build
```

This creates `public/build/` with compiled CSS and JS. If you skip `npm run build`, the site still works using CDN Tailwind (see `resources/views/layouts/app.blade.php`).

---

## 2. What to upload

Upload the **entire project** except:

- `node_modules/`
- `.git/` (optional)

**No .env** — use `config/site.php` (see section 4).

Make sure these are included:

- `public/build/` (if you ran `npm run build`)
- `public/models/` (PDFs)
- `vendor/`
- `storage/` and `bootstrap/cache/` (empty dirs are ok; we’ll set permissions)

---

## 3. cPanel setup

### PHP

- **PHP 8.3** (cPanel → **Select PHP Version** or **MultiPHP** → **ea-php83**).

### Option A: Document root = `public` (recommended)

1. Upload the project to a folder, e.g. `cam-solution`, in your home or `public_html`:
   - `public_html/cam-solution/`  
   or  
   - `cam-solution/` in home (e.g. `/home/username/cam-solution/`).

2. In cPanel: **Domains** → your domain → **Document Root (or **Edit** next to the domain).
   - Set it to the `public` folder of the app, e.g.:
     - `public_html/cam-solution/public`  
     or  
     - `cam-solution/public`  
   depending on where you uploaded.

3. Your URLs will be: `https://yourdomain.com/` (no `/cam-solution` in the path).

---

### Option B: Document root must stay `public_html`

Use this when you **cannot** change the document root from `public_html`.

1. **Upload the Laravel app** (without the `public` folder’s contents) to a folder **next to** `public_html`, e.g.:
   - `/home/username/cam-solution/`  
   with: `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `vendor/`, `artisan`, `composer.json`, etc.  
   Do **not** put the `public` folder itself here; we only need its contents in `public_html`.

2. **Copy the contents of `public/` into `public_html`** (not the `public` folder itself):
   - `.htaccess`, `favicon.ico`, `robots.txt`
   - `build/` (if you ran `npm run build`)
   - `models/`

3. **Use the cPanel-specific `index.php`** so Laravel can find the app:
   - Copy `deploy/index-public_html.php` to `public_html/index.php` (this replaces the default `public/index.php`).  
   - **Edit** the `LARAVEL_APP_DIR` line if your folder is not `cam-solution`:
     ```php
     define('LARAVEL_APP_DIR', __DIR__ . '/../cam-solution');
     ```
     Replace `cam-solution` with the real folder name (e.g. `laravel` or `app`).

---

## 4. Configure the app on the server

### 4.1 Edit `config/site.php` (no .env)

In the **Laravel root**, edit `config/site.php`. Set at least:

- **url** — e.g. `https://yourdomain.com` (or `https://yourdomain.com/pavan` if in a subfolder)
- **path** — subfolder name (e.g. `pavan`) or `''` when at domain root
- **asset_url** — same as `url` when in a subfolder
- **key** — run `php artisan key:generate --show` (locally) and paste the `base64:...` value. Required.

`name`, `env`, `debug` have defaults. For MySQL, see the `db_*` commented block in `config/site.php` and wire into `config/database.php`.

### 4.2 Permissions

From SSH (or a “Terminal” in cPanel), in the Laravel root:

```bash
chmod -R 755 storage bootstrap/cache
# If the web server user is different, your host may require:
# chown -R username:username storage bootstrap/cache
```

(Replace `username` with your cPanel user if the host expects it.)

### 4.3 Caches (PHP 8.3 / ea-php83)

In the **Laravel root** (same folder as `artisan`):

```bash
php artisan config:cache
php artisan route:cache
```

(Do **not** run `key:generate` on the server; set **key** in `config/site.php` as in 4.1.)

If you use a database and migrations:

```bash
php artisan migrate --force
```

---

## 5. .htaccess

Laravel’s `public/.htaccess` is already set up. For **Option A**, it will be used from `public` as the document root. For **Option B**, the same `.htaccess` must be in `public_html` (you already copied it with the rest of `public`).

If you get 500 errors, check:

- **mod_rewrite** is enabled (cPanel or host).
- No conflicting rules in `public_html/.htaccess` or a parent.

---

## 6. Quick checklist

| Step | Action |
|------|--------|
| 1 | `composer install --no-dev --optimize-autoloader` |
| 2 | `npm ci && npm run build` |
| 3 | Upload project (without `node_modules`, without `.env`) |
| 4 | **Option A:** Set document root to `.../public` **Option B:** Put `public` contents in `public_html`, use `deploy/index-public_html.php` as `index.php` and set `LARAVEL_APP_DIR` |
| 5 | Create `.env` from `.env.example` in the Laravel root |
| 6 | `chmod -R 755 storage bootstrap/cache` |
| 7 | `php artisan key:generate` |
| 8 | `php artisan config:cache` and `php artisan route:cache` |

---

## 7. If you can’t run `composer` or `npm` on the server

- Run `composer install --no-dev --optimize-autoloader` and `npm run build` on your **local** machine (or in CI).
- Upload the whole project including `vendor/` and `public/build/`.

You still need to run on the server (via SSH or cPanel Terminal):

- `php artisan key:generate`
- `php artisan config:cache`
- `php artisan route:cache`

---

## 8. Troubleshooting

- **500 error:** Check `storage/logs/laravel.log`; ensure `storage` and `bootstrap/cache` are writable and `mod_rewrite` is on.
- **Blank page, no CSS:** For Option B, ensure `public/build/` was copied into `public_html/build/`. If you didn’t run `npm run build`, the site falls back to CDN Tailwind.
- **“Please provide a valid cache path”:** Laravel root or paths are wrong; for Option B, verify `LARAVEL_APP_DIR` in `public_html/index.php` and that `storage` and `bootstrap/cache` exist and are writable.
