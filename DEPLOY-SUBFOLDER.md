# Deploy in a subfolder (e.g. /pavan)

Use this when the app must run at `https://yourdomain.com/pavan/` and the project is in `public_html/pavan/`.

The problem: the server was showing "Index of /pavan" because it served the Laravel **root** instead of `public/index.php`. Fix: **merge** the contents of `public/` into the subfolder and use a custom `index.php` so the app runs from `pavan/` directly.

---

## 0. Requirements

- **PHP 8.3** (cPanel: Select PHP Version → **ea-php83**).
- **No .env** — all settings in `config/site.php`.

---

## 1. Upload updated files

Upload these so the server has the latest logic:

- `config/site.php`
- `config/app.php`
- `deploy/index-subfolder.php`
- `routes/web.php`
- `app/Providers/AppServiceProvider.php`

Then continue with the merge below.

---

## 2. Merge `public/` into `pavan/`

On the server, in `public_html/pavan/`:

### Using cPanel File Manager

1. **Add `index.php`**  
   - Upload `deploy/index-subfolder.php`  
   - Rename it to `index.php` (at the same level as `app/`, `vendor/`, etc., i.e. inside `pavan/`).

2. **Copy `public/.htaccess`**  
   - Copy `pavan/public/.htaccess` to `pavan/.htaccess` (so `.htaccess` is next to `index.php`).

3. **Move from `public/` into `pavan/`**
   - `public/build/` → `pavan/build/`
   - `public/models/` → `pavan/models/`
   - `public/favicon.ico` → `pavan/favicon.ico`
   - `public/robots.txt` → `pavan/robots.txt`

4. **Remove `public/`**  
   - Delete the (now empty) `pavan/public/` folder.

### Using SSH

```bash
cd ~/public_html/pavan

cp deploy/index-subfolder.php index.php
cp public/.htaccess .htaccess
mv public/build . 2>/dev/null || true
mv public/models . 2>/dev/null || true
cp public/favicon.ico . 2>/dev/null || true
cp public/robots.txt . 2>/dev/null || true
rm -rf public
```

---

## 3. Configure `config/site.php`

Edit `pavan/config/site.php` on the server. Set at least:

- **url** — full URL including subfolder, e.g. `https://camsolutions.co.in/pavan`
- **path** — subfolder name, e.g. `pavan` (empty string if at domain root)
- **asset_url** — same as `url` when in a subfolder
- **key** — **REQUIRED.** Generate locally: `php artisan key:generate --show` and paste the `base64:...` value. Do not leave empty in production.

`name`, `env`, `debug` have defaults; change as needed.

---

## 4. Permissions and Artisan (PHP 8.3)

In `pavan/` (Laravel root), using **ea-php83**:

```bash
chmod -R 755 storage bootstrap/cache
php artisan config:cache
php artisan route:cache
```

You do **not** run `php artisan key:generate` on the server; set `config/site.php` → `key` as in step 3.

---

## 5. Resulting layout (example)

```
public_html/pavan/
├── index.php          ← deploy/index-subfolder.php
├── .htaccess          ← from public/.htaccess
├── build/             ← from public/build/
├── models/            ← from public/models/
├── favicon.ico
├── robots.txt
├── app/
├── bootstrap/
├── config/
├── vendor/
├── storage/
└── ...
```

---

## 6. Check

- `https://camsolutions.co.in/pavan/` → Home  
- `https://camsolutions.co.in/pavan/contact` → Contact  
- `https://camsolutions.co.in/pavan/build/...` → CSS/JS (if you ran `npm run build`)

---

## 7. If you didn’t run `npm run build`

The app will use CDN Tailwind and may work without `pavan/build/`. You can skip moving `public/build/`; set **asset_url** in `config/site.php` if you add `build/` later.

---

## 8. Clean up (optional)

- Remove `__MACOSX/` if it was unpacked from a zip.  
- Do not upload `node_modules/` or `.git/`. **No .env** — everything is in `config/site.php`.
