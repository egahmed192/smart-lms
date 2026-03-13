# Moodle check scripts

Run these anytime to verify your Moodle setup. Repeat as often as you like (e.g. daily, after changes).

## From command line

```bash
# Single checks
php health_check.php
php upgrade_status.php

# Run all checks
php run_all.php
```

From anywhere (use full path):

```bash
php C:\xampp\htdocs\moodle\public\scripts\health_check.php
php C:\xampp\htdocs\moodle\public\scripts\run_all.php
```

## From browser

- **Health check:** http://localhost:8080/moodle/scripts/health_check.php  
- **Upgrade status:** http://localhost:8080/moodle/scripts/upgrade_status.php  

(Ensure `scripts/` is under `public/` so the URL works.)

## What each script does

| Script | Purpose |
|--------|--------|
| `health_check.php` | Config, DB connection, dataroot, DB version, plugin dirs |
| `upgrade_status.php` | Compares code version vs DB version; says if upgrade needed |
| `plugin_registry_check.php` | Lists our plugins and whether they’re installed in DB |
| `run_all.php` | Runs health_check + upgrade_status + plugin_registry_check |

## Run “for life”

- **Windows:** Double-click `run_all.bat` (or run it from Task Scheduler).
- **Cron (Linux):** `0 8 * * * php /path/to/moodle/public/scripts/run_all.php >> /var/log/moodle_checks.log 2>&1`

Fix any FAIL or “Upgrade needed: YES” reported by the scripts, then run again to confirm.

**Note:** If you see “Module mysqli is already loaded” in PHP output, it’s a PHP.ini duplicate; it doesn’t affect the checks. To hide it, ensure `extension=mysqli` appears only once in your `php.ini`.
