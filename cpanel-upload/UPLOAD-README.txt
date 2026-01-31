CAM Solutions – cPanel Upload Folder
=====================================

1. Upload this entire folder to cPanel (e.g. public_html/yourfolder/).

2. Set PHP 8.3: cPanel → Select PHP Version → ea-php83.

3. Edit config/site.php on the server:
   - url: your full URL (e.g. https://yourdomain.com/yourfolder)
   - path: your subfolder name (e.g. yourfolder)
   - asset_url: same as url
   - key: run locally: php artisan key:generate --show (paste the base64:... value)
   - env: 'production'
   - debug: false
   - db_host, db_database, db_username, db_password: your MySQL details

4. Permissions: set 755 (Recurse) on storage and bootstrap/cache.

5. In browser open: https://yourdomain.com/yourfolder/run-migrate.php?run=1
   Then delete run-migrate.php from the server.

6. In browser open: https://yourdomain.com/yourfolder/run-cache.php?run=1
   Then delete run-cache.php from the server.

7. Test: https://yourdomain.com/yourfolder/

Do not upload: .env (not used; config/site.php is used instead).
