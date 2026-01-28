# How to Deploy on cPanel

Quick guide. Full details: **DEPLOY-CPANEL.md** (subfolder, no SSH) or **DEPLOYMENT.md** (Options A/B).

---

## 1. Build locally

```bash
composer install --no-dev --optimize-autoloader
```

(No Node/npm required – the app uses CDN Tailwind only.)

Generate app key (copy the output):

```bash
php artisan key:generate --show
```

---

## 2. Create database on cPanel

1. cPanel → **MySQL® Databases**
2. Create a database (e.g. `username_camsolution`)
3. Create a user and add it to the database (All Privileges)
4. Note: host (often `localhost`), database name, username, password

---

## 3. Upload project

1. Create a folder in `public_html`, e.g. `cam` or `pavan`
2. Upload the **whole project** into `public_html/cam/` (or your folder name)
3. **Do not upload:** `node_modules/`, `.git/`, `.env`
4. **Must have:** `vendor/`, `public/models/`, `app/`, `config/`, `routes/`, `storage/`, `bootstrap/`, `deploy/` (no `public/build/` – app uses CDN CSS)

---

## 4. PHP version

cPanel → **Select PHP Version** → **ea-php83**

(Optional) For PDF uploads: in **PHP Options** or `.user.ini` in the document root set:
- `upload_max_filesize = 15M`
- `post_max_size = 20M`

---

## 5. Merge `public/` into your folder (subfolder setup)

So `https://yourdomain.com/cam/` works:

| Step | Action (in `public_html/cam/`) |
|------|---------------------------------|
| 1 | Copy **`deploy/index-subfolder.php`** into the app folder as **`index.php`** (overwrite the existing one). Do **not** use Laravel’s default `public/index.php` — it looks for `vendor/` in the parent and will fail. |
| 2 | Copy `public/.htaccess` → to `cam/.htaccess` |
| 3 | Move `public/models/` → `cam/models/` |
| 4 | Move `public/favicon.ico`, `public/robots.txt`, `public/.user.ini` → into `cam/` |
| 5 | Delete the empty `public/` folder |
| 6 | Copy `deploy/run-cache.php` → to `cam/run-cache.php` |

---

## 6. Configure on the server

Edit **`config/site.php`** in File Manager:

| Key | Value |
|-----|--------|
| **url** | `https://yourdomain.com/cam` |
| **path** | `cam` (your folder name) |
| **asset_url** | `https://yourdomain.com/cam` |
| **key** | Paste the `base64:...` from step 1 |
| **env** | `'production'` |
| **debug** | `false` |
| **db_connection** | `'mysql'` |
| **db_host** | `localhost` (or your MySQL host) |
| **db_database** | Your database name |
| **db_username** | Your database user |
| **db_password** | Your database password |

---

## 7. Permissions

In File Manager, set **755** and “Recurse into subdirectories” for:

- `storage`
- `bootstrap/cache`

---

## 8. Run cache (no SSH)

In browser open:

```
https://yourdomain.com/cam/run-cache.php?run=1
```

When you see “Done”, **delete `run-cache.php`** from the server.

---

## 9. Run migrations (auth + videos tables)

**If cPanel has Terminal:**

```bash
cd ~/public_html/cam
php artisan migrate --force
```

**If no Terminal:** Use **phpMyAdmin** to create the tables, or run migrations from your computer against the server database (see `database/migrations/` for the SQL structure). Alternatively use the one-time script `deploy/run-migrate.php` (see DEPLOY-CPANEL.md).

---

## 10. Test

Open: **https://yourdomain.com/cam/**

---

## Checklist

- [ ] Build: `composer install --no-dev`, copy app key
- [ ] Create MySQL database and user in cPanel
- [ ] Upload project to `public_html/cam/` (no `node_modules`, no `.env`)
- [ ] PHP 8.3 (ea-php83)
- [ ] Merge: `index.php`, `.htaccess`, `models/` into `cam/`; delete `public/`; add `run-cache.php`
- [ ] Edit `config/site.php`: url, path, asset_url, key, db_*, env, debug
- [ ] Permissions: `storage`, `bootstrap/cache` → 755
- [ ] Open `run-cache.php?run=1` in browser, then delete `run-cache.php`
- [ ] Run `php artisan migrate --force` (Terminal or phpMyAdmin)
- [ ] Test the site

---

## Troubleshooting

**Error: `Failed to open stream: .../../vendor/autoload.php: No such file or directory`**

Your document root is the folder that contains the **full** Laravel app (with `vendor/`, `app/`, etc. in the **same** folder as `index.php`). You must use **`deploy/index-subfolder.php`** as `index.php`, not Laravel’s default `public/index.php`.

**Fix:** In cPanel File Manager, open **`deploy/index-subfolder.php`**, select all and copy. Then open **`index.php`** in the app root (same folder as `vendor/`, `app/`) and replace its entire content with the copied content. Save. Reload the site.
