# Edumasr Academy Theme & Plugin – Installation

This package provides a custom Moodle theme and optional local plugin to implement the EDUMASR ACADEMY dashboard design. **Only admins can change colors and styles** via Site administration → Appearance → Themes → Edumasr Academy.

## Requirements

- Moodle 4.x (compatible with Boost theme)
- PHP 8.1+

## Installation

### 1. Copy files

**Theme**

```bash
# From your home directory
cp -r ~/edumasr-moodle-theme/theme/edumasr /path/to/moodle/theme/

# If your Moodle root is under public/:
cp -r ~/edumasr-moodle-theme/theme/edumasr /path/to/moodle/public/theme/
```

**Local plugin (optional)**

```bash
cp -r ~/edumasr-moodle-theme/local/edumasrdashboard /path/to/moodle/local/
```

**Blocks (optional)**

```bash
cp -r ~/edumasr-moodle-theme/blocks/edumasr_recentcourses /path/to/moodle/blocks/
cp -r ~/edumasr-moodle-theme/blocks/edumasr_progressoverview /path/to/moodle/blocks/
```

### 2. Fix permissions (if needed)

If the Moodle directory is owned by root:

```bash
sudo chown -R www-data:www-data /path/to/moodle/theme/edumasr
sudo chown -R www-data:www-data /path/to/moodle/local/edumasrdashboard
# Or use your web server user instead of www-data
```

### 3. Upgrade Moodle

1. Visit **Site administration → Notifications**
2. Confirm the upgrade when prompted
3. Clear caches: **Site administration → Development → Purge all caches**

### 4. Enable the theme

1. Go to **Site administration → Appearance → Themes**
2. Select **Edumasr Academy**
3. Go to **Site administration → Appearance → Themes → Edumasr Academy** to set colors:
   - **Primary colour** – Main accent (default: #ff7518)
   - **Accent colour** – Secondary highlight (default: #f0ad4e)
   - **Border radius** – Card/button rounding (e.g. .5rem)
   - **Brand colour** – Logo/accent

## Dashboard layout (full EDUMASR design)

The theme uses a custom dashboard layout with fixed sidebar and 2-column grid:

- Applies the orange/yellow color scheme from your reference
- Uses rounded cards and Bootstrap-based layout
- Reuses Moodle’s standard blocks

### Adding dashboard blocks

In **My Moodle** (Dashboard), turn editing on and add blocks such as:

- **Recently accessed courses** (block_edumasr_recentcourses – included, server-side rendered)
- **Course progress overview** (block_edumasr_progressoverview – included, shows completion progress)
- **Timeline**
- **Online users**
- **Recent activity**
- **Course overview**
- **HTML block** – for a custom welcome card (text and button)

## Blocks

The package includes two custom blocks:

### Welcome banner (block_edumasr_welcomebanner)

- Displays “Welcome back, [name]!” with View Schedule button
- Only visible when logged in (not guests)
- Hidden block title by default
- Spans full width above other blocks on the Edumasr dashboard
- Add as first block for the greeting above the content

### Recently accessed courses (block_edumasr_recentcourses)

- Renders server-side (no JavaScript/AJAX) for faster load
- Uses the same course cards as Moodle’s core block
- Configurable at **Site administration → Plugins → Blocks → Recently accessed courses**

### Course progress overview (block_edumasr_progressoverview)

- Shows enrolled courses with completion progress bars
- Only displays courses that have completion tracking enabled
- Requires completion tracking (Site administration → Advanced features)
- Configurable at **Site administration → Plugins → Blocks → Course progress overview**:
  - **Display categories** – Show course category next to each course
  - **Maximum number of courses** – How many to show (default: 10)

Add blocks to the Dashboard via **Turn editing on → Add block**.

## Directory structure

```
theme/edumasr/
├── config.php
├── lib.php
├── settings.php
├── version.php
├── layout/
│   ├── dashboard.php    # Custom dashboard layout
│   ├── drawers.php      # Inherits from Boost
│   └── ...
├── lang/en/
├── scss/
│   ├── preset/edumasr.scss
│   └── edumasr-dashboard.scss
└── templates/
    └── dashboard.mustache
```

## Customization

- All color/style settings are admin-only in the theme settings.
- To tweak styles further, use **Raw initial SCSS** and **Raw SCSS** in the theme’s advanced settings.
- The preset uses Bootstrap variables (`$primary`, `$secondary`, etc.) so Bootstrap components pick up the theme colors.
