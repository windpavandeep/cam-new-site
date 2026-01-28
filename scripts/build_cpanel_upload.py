#!/usr/bin/env python3
"""
Build a cPanel-ready folder for CAM Solution Laravel app.

Run from project root:
    python3 scripts/build_cpanel_upload.py

Or specify paths:
    python3 scripts/build_cpanel_upload.py --project . --output cpanel-upload

The output folder can be zipped and uploaded to cPanel (e.g. public_html/yourfolder/).
PHP 8.3+ required on server. Configure config/site.php after upload.
"""

import argparse
import os
import shutil
import subprocess
import sys
from pathlib import Path


# Default output folder name
DEFAULT_OUTPUT = "cpanel-upload"

# Directories to copy (from project root)
DIRS_TO_COPY = [
    "app",
    "bootstrap",
    "config",
    "database",
    "resources",
    "routes",
    "storage",
    "vendor",
]

# Single files to copy from project root
FILES_TO_COPY = [
    "artisan",
    "composer.json",
    "composer.lock",
]

# Public assets to merge into output root (no public/ subfolder)
PUBLIC_ITEMS = [
    ".htaccess",
    ".user.ini",
    "build",
    "favicon.ico",
    "images",
    "models",
    "robots.txt",
]

# Paths to exclude from copy (relative to their parent)
EXCLUDE_PATTERNS = [
    "node_modules",
    ".git",
    ".env",
    ".env.backup",
    ".env.production",
    ".env.example",
    "*.log",
    ".DS_Store",
    "Homestead.json",
    "Homestead.yaml",
    "Thumbs.db",
    ".phpunit.result.cache",
    ".phpunit.cache",
    "auth.json",
    ".cursorrules",
    ".editorconfig",
    ".gitattributes",
    ".vscode",
    ".idea",
    ".nova",
    ".fleet",
    ".zed",
    "phpunit.xml",
    "vite.config.js",
    "package.json",
    "package-lock.json",
    "readme.md",
    "DEPLOY-CPANEL.md",
    "DEPLOY-OPTION-A.md",
    "DEPLOY-SUBFOLDER.md",
    "DEPLOYMENT.md",
    "HOW-TO-DEPLOY-CPANEL.md",
    "scripts",
]

# Within storage: keep structure but exclude log files and cache data
STORAGE_EXCLUDE = ["*.log", "*.key"]


def should_exclude(name: str, path: Path, project_root: Path) -> bool:
    """Return True if this path should be excluded."""
    try:
        rel = path.relative_to(project_root)
        parts = rel.parts
    except ValueError:
        parts = path.parts

    for pat in EXCLUDE_PATTERNS:
        if pat.startswith("*"):
            if name.endswith(pat[1:]):
                return True
        elif name == pat or name.startswith(pat + "."):
            return True

    # Exclude bootstrap/cache/*.php (compiled) but keep .gitignore
    if "bootstrap" in parts and "cache" in parts and path.is_file():
        if name.endswith(".php"):
            return True
    # Exclude storage/framework/views/*.php and cache data
    if "storage" in parts and "framework" in parts:
        if "views" in parts and name.endswith(".php"):
            return True
        if "cache" in parts and "data" in parts:
            return True
    return False


def copy_tree(src: Path, dst: Path, project_root: Path) -> None:
    """Copy directory tree with exclusions."""
    dst.mkdir(parents=True, exist_ok=True)
    for item in src.iterdir():
        if should_exclude(item.name, item, project_root):
            continue
        if item.is_dir():
            copy_tree(item, dst / item.name, project_root)
        else:
            shutil.copy2(item, dst / item.name)


def run_cmd(cmd: list[str], cwd: Path) -> bool:
    """Run a command; return True on success."""
    print(f"  Running: {' '.join(cmd)}")
    r = subprocess.run(cmd, cwd=cwd)
    return r.returncode == 0


def main() -> int:
    parser = argparse.ArgumentParser(description="Build cPanel upload folder for CAM Solution")
    parser.add_argument("--project", type=Path, default=Path.cwd(), help="Project root path")
    parser.add_argument("--output", type=Path, default=Path(DEFAULT_OUTPUT), help="Output folder name or path")
    parser.add_argument("--no-composer", action="store_true", help="Skip composer install")
    parser.add_argument("--no-npm", action="store_true", help="Skip npm install and npm run build")
    args = parser.parse_args()

    project = args.project.resolve()
    if not (project / "artisan").exists() or not (project / "composer.json").exists():
        print("Error: Project root must contain artisan and composer.json", file=sys.stderr)
        return 1

    out = args.output.resolve()
    if out.is_dir():
        print(f"Removing existing output folder: {out}")
        shutil.rmtree(out)
    out.mkdir(parents=True)

    # Optional: run composer and npm
    if not args.no_composer:
        if not run_cmd(["composer", "install", "--no-dev", "--optimize-autoloader"], project):
            print("Warning: composer install failed; continuing with existing vendor/", file=sys.stderr)
    if not args.no_npm:
        if (project / "package.json").exists():
            if not run_cmd(["npm", "install"], project):
                print("Warning: npm install failed", file=sys.stderr)
            elif not run_cmd(["npm", "run", "build"], project):
                print("Warning: npm run build failed", file=sys.stderr)

    public_dir = project / "public"
    if not (public_dir / "build").exists():
        print("Warning: public/build/ not found. Run: npm run build", file=sys.stderr)

    # Copy .php-version so hosts know PHP 8.3 is required
    phpver = project / ".php-version"
    if phpver.is_file():
        shutil.copy2(phpver, out / ".php-version")
        print("Copied .php-version")

    # Copy directories
    for d in DIRS_TO_COPY:
        src = project / d
        if not src.is_dir():
            print(f"Skipping missing dir: {d}")
            continue
        print(f"Copying {d}/ ...")
        copy_tree(src, out / d, project)

    # Copy single files
    for f in FILES_TO_COPY:
        src = project / f
        if src.is_file():
            shutil.copy2(src, out / f)
            print(f"Copied {f}")

    # Copy deploy/ (for reference; we use its contents for index.php and run-*.php)
    deploy_src = project / "deploy"
    if deploy_src.is_dir():
        (out / "deploy").mkdir(exist_ok=True)
        for f in deploy_src.iterdir():
            if f.is_file():
                shutil.copy2(f, out / "deploy" / f.name)
        print("Copied deploy/")

    # Merge public/ into output root
    for name in PUBLIC_ITEMS:
        src = public_dir / name
        if not src.exists():
            continue
        dst = out / name
        if src.is_file():
            shutil.copy2(src, dst)
            print(f"Merged public/{name}")
        else:
            if dst.exists():
                shutil.rmtree(dst)
            shutil.copytree(src, dst, dirs_exist_ok=True)
            print(f"Merged public/{name}/")

    # Write index.php from deploy/index-subfolder.php
    index_src = project / "deploy" / "index-subfolder.php"
    if index_src.is_file():
        (out / "index.php").write_text(index_src.read_text())
        print("Written index.php from deploy/index-subfolder.php")

    # Ensure run-cache.php and run-migrate.php are in root
    for script in ["run-cache.php", "run-migrate.php"]:
        src = project / "deploy" / script
        if src.is_file():
            shutil.copy2(src, out / script)
            print(f"Copied {script} to output root")

    # README for upload
    readme = """CAM Solution – cPanel Upload Folder
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
"""
    (out / "UPLOAD-README.txt").write_text(readme, encoding="utf-8")
    print("Written UPLOAD-README.txt")

    # Short reminder for config/site.php
    config_note = """Edit this file on the server after upload.

Required keys:
  url         => 'https://yourdomain.com/yourfolder',
  path        => 'yourfolder',
  asset_url   => 'https://yourdomain.com/yourfolder',
  key         => 'base64:...',   (from: php artisan key:generate --show)
  env         => 'production',
  debug       => false,
  db_host     => 'localhost',
  db_database => 'your_db',
  db_username => 'your_user',
  db_password => 'your_password',
"""
    (out / "CONFIG-SITE.txt").write_text(config_note, encoding="utf-8")
    print("Written CONFIG-SITE.txt")

    print(f"\nDone. Output folder: {out}")
    print("Zip this folder and upload to cPanel, or upload contents into your domain folder.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
