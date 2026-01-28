# Build cPanel upload folder

From the **project root** (parent of `scripts/`), run:

```bash
python3 scripts/build_cpanel_upload.py
```

This will:

1. Run `composer install --no-dev` and `npm run build` (unless you pass `--no-composer` / `--no-npm`)
2. Create a folder **`cpanel-upload/`** with:
   - All app files (app/, config/, routes/, vendor/, etc.)
   - **No** `node_modules`, `.git`, `.env`
   - **Merged** `public/` contents: `build/`, `models/`, `images/`, `.htaccess`, `favicon.ico`, `robots.txt`, `.user.ini`
   - **`index.php`** from `deploy/index-subfolder.php` (so `vendor/` is in the same folder)
   - **`run-cache.php`** and **`run-migrate.php`** in the root
   - **`UPLOAD-README.txt`** and **`CONFIG-SITE.txt`** with instructions

3. You can **zip** `cpanel-upload/` and upload to cPanel, or upload its contents into your domain folder (e.g. `public_html/yourfolder/`).

**Options:**

- `--output FOLDER` – use a different output folder (default: `cpanel-upload`)
- `--no-composer` – skip `composer install`
- `--no-npm` – skip `npm install` and `npm run build`

**Requires:** Python 3.6+
