# Welcome banner block (EDUMASR)

Displays a welcome message with animated waving hand and "View Schedule" button on the Dashboard (My Moodle).

## Install

1. **Copy the block into Moodle**
   - From your project root, the Moodle block folder is usually `sites/moodle/public/blocks/` or `public/moodle/blocks/` (depending on your URL).
   - Copy the entire `edumasr_welcomebanner` folder there:
     ```bash
     cp -r edumasr-moodle-theme/blocks/edumasr_welcomebanner /path/to/moodle/blocks/
     ```
   - If the target is not writable: `sudo cp -r edumasr-moodle-theme/blocks/edumasr_welcomebanner /path/to/moodle/blocks/`
   - Example (run from project root; use your actual moodle path):  
     `sudo cp -r edumasr-moodle-theme/blocks/edumasr_welcomebanner sites/moodle/public/blocks/`
   - If the block folder already exists, copy contents:  
     `sudo cp -r edumasr-moodle-theme/blocks/edumasr_welcomebanner/* sites/moodle/public/blocks/edumasr_welcomebanner/`  
     Then delete any old helper scripts from the block folder: `add_to_my.php`, `install_again.php`, `block_check.php`.

2. **Register the block in Moodle**
   - Log in as admin.
   - Go to **Site administration → Notifications**. Moodle will detect the new block and show an upgrade page.
   - Click **Upgrade Moodle database now** and complete the upgrade.

3. **Purge caches**
   - **Site administration → Development → Purge all caches**.

4. **Add the block on Dashboard**
   - Go to **Dashboard (My Moodle)**.
   - Turn **editing on**.
   - Click **Add a block** and choose **Welcome banner**.
   - The block is added to the main content area; you can move it if your theme supports it.

## Requirements

- Moodle 4.5+ (requires 2025092600).
- Block appears on **My** (dashboard) and **Site** (front page) only.

## Capabilities

- **Add a Welcome banner block to Dashboard** (`block/edumasr_welcomebanner:myaddinstance`) — follows "Manage my dashboard blocks" permission.

## Files (do not remove)

- `block_edumasr_welcomebanner.php` — block class
- `version.php` — plugin version
- `db/install.php` — install hook
- `db/access.php` — capabilities
- `lang/en/block_edumasr_welcomebanner.php` — English strings
- `classes/output/main.php` — renderable
- `classes/output/renderer.php` — renderer
- `templates/main.mustache` — template
- `styles.css` — block styles
