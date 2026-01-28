<!-- 1G%,Ebk$7zc78@hQ -->


# Deploy on cPanel (no .env, no Terminal/SSH)

All settings are in **`config/site.php`**. Do not use `.env`.  
**Shared hosting:** no Terminal/SSH — use File Manager + `run-cache.php` only.

---

## Step 1. Build on your computer

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

---

## Step 2. Get the app key (one time)

```bash
php artisan key:generate --show
```

Copy the full line (starts with `base64:...`). You will paste it into `config/site.php` on the server.

---

## Step 3. Upload to cPanel

1. Create folder `pavan` in `public_html`.
2. Upload the **whole project** into `public_html/pavan/`.

**Do not upload:** `node_modules/`, `.git/`, `.env`  
**Must have:** `vendor/`, `public/build/`, `public/models/`, `config/site.php`, `config/app.php`, `deploy/` (with `index-subfolder.php`, `run-cache.php`, `run-migrate.php`), `routes/`, `app/`, `bootstrap/`, `storage/`, etc.

---

## Step 4. Set PHP 8.3

cPanel → **Select PHP Version** (or **MultiPHP**) → choose **ea-php83**.

---

## Step 5. Merge `public/` into `pavan/` (subfolder fix)

So `https://yourdomain.com/pavan/` works (no "Index of /pavan"):

**In `public_html/pavan/`:**

| Action | What to do |
|--------|------------|
| 1 | Copy `deploy/index-subfolder.php` → rename to `index.php` (same folder as `app/`, `vendor/`) |
| 2 | Copy `public/.htaccess` → to `pavan/.htaccess` (next to the new `index.php`) |
| 3 | Move `public/build/` → `pavan/build/` |
| 4 | Move `public/models/` → `pavan/models/` |
| 5 | Move `public/favicon.ico` and `public/robots.txt` → into `pavan/` |
| 6 | Delete the `public/` folder |
| 7 | Copy `deploy/run-cache.php` and `deploy/run-migrate.php` → to `pavan/` (same folder as `index.php`, `artisan`) |

---

## Step 6. Edit `config/site.php` on the server

Open **`pavan/config/site.php`** in cPanel File Manager (or SSH) and set:

| Key | Example (for `https://camsolutions.co.in/pavan`) |
|-----|--------------------------------------------------|
| **url** | `'https://camsolutions.co.in/pavan'` |
| **path** | `'pavan'` |
| **asset_url** | `'https://camsolutions.co.in/pavan'` |
| **key** | `'base64:...'` ← paste the full `base64:...` from Step 2 |
| **env** | `'production'` |
| **debug** | `false` |

---

## Step 6b. Database (auth + videos)

1. cPanel → **MySQL® Databases** → create a database and user; add user to database (All Privileges).
2. In `config/site.php` set: **db_host** (e.g. `localhost`), **db_database**, **db_username**, **db_password**.
3. Run migrations (no Terminal): in browser open:
   ```
   https://camsolutions.co.in/pavan/run-migrate.php?run=1
   ```
   When you see "Done", **delete `run-migrate.php`** from the server.

---

## Step 7. Permissions (File Manager only, no Terminal)

In cPanel **File Manager** → `public_html/pavan/`:

1. **storage**  
   - Right‑click `storage` → **Change Permissions** (or **Permissions**).  
   - Set **755**, enable **Recurse into subdirectories** → **Change Permissions**.

2. **bootstrap/cache**  
   - Open `bootstrap/`, right‑click `cache` → **Change Permissions** → **755** → **Recurse into subdirectories** → **Change Permissions**.

---

## Step 8. Run cache (no Terminal)

1. In the browser open:
   ```
   https://camsolutions.co.in/pavan/run-cache.php?run=1
   ```
2. You should see: `config:cache: OK`, `route:cache: OK`, and **"Done. DELETE run-cache.php..."**.
3. In File Manager go to `public_html/pavan/` and **delete `run-cache.php`** (security).

---

## Step 9. Test

Open: **https://camsolutions.co.in/pavan/**

---

## Checklist (no Terminal)

| # | Task | Done |
|---|------|------|
| 1 | `composer install --no-dev`, `npm run build` | |
| 2 | `php artisan key:generate --show` → copy `base64:...` | |
| 3 | Upload project to `public_html/pavan/` (no `node_modules`, no `.env`) | |
| 4 | cPanel: PHP → **ea-php83** | |
| 5 | Merge: `index.php`, `.htaccess`, `build/`, `models/` into `pavan/`; delete `public/`; copy `run-cache.php` and `run-migrate.php` → `pavan/` | |
| 6 | Edit `config/site.php`: **url**, **path**, **asset_url**, **key**, **db_*** (and **env**=production, **debug**=false) | |
| 7 | File Manager: **storage** and **bootstrap/cache** → Permissions **755** (Recurse into subdirectories) | |
| 8 | Open `https://yoursite.com/pavan/run-migrate.php?run=1` → then **delete `run-migrate.php`** | |
| 9 | Open `https://yoursite.com/pavan/run-cache.php?run=1` → then **delete `run-cache.php`** | |

---

## If you skip `npm run build`

The site still works using CDN CSS. You can omit moving `public/build/` in Step 5. `asset_url` in `config/site.php` is still required when in a subfolder.
