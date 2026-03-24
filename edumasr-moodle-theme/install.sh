#!/bin/bash
# Edumasr Academy Theme - Install script
# Usage: ./install.sh /path/to/moodle
# Example: ./install.sh /Users/marwansalem/sites/moodle/public

set -e

MOODLE_ROOT="${1:-}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ -z "$MOODLE_ROOT" ]; then
    echo "Usage: $0 /path/to/moodle"
    echo "Example: $0 /Users/marwansalem/sites/moodle/public"
    exit 1
fi

if [ ! -d "$MOODLE_ROOT" ]; then
    echo "Error: Moodle root '$MOODLE_ROOT' does not exist."
    exit 1
fi

THEME_SRC="$SCRIPT_DIR/theme/edumasr"
THEME_DEST="$MOODLE_ROOT/theme/edumasr"
LOCAL_SRC="$SCRIPT_DIR/local/edumasrdashboard"
LOCAL_DEST="$MOODLE_ROOT/local/edumasrdashboard"
BLOCK_SRC="$SCRIPT_DIR/blocks/edumasr_recentcourses"
BLOCK_DEST="$MOODLE_ROOT/blocks/edumasr_recentcourses"
PROGRESS_SRC="$SCRIPT_DIR/blocks/edumasr_progressoverview"
PROGRESS_DEST="$MOODLE_ROOT/blocks/edumasr_progressoverview"

echo "Installing Edumasr Academy theme to $MOODLE_ROOT..."

# Theme
if [ -d "$THEME_SRC" ]; then
    mkdir -p "$(dirname "$THEME_DEST")"
    cp -r "$THEME_SRC" "$THEME_DEST"
    echo "  - Theme installed to $THEME_DEST"
else
    echo "  - Warning: Theme source not found at $THEME_SRC"
fi

# Local plugin
if [ -d "$LOCAL_SRC" ]; then
    mkdir -p "$(dirname "$LOCAL_DEST")"
    cp -r "$LOCAL_SRC" "$LOCAL_DEST"
    echo "  - Local plugin installed to $LOCAL_DEST"
fi

# Recently accessed courses block
if [ -d "$BLOCK_SRC" ]; then
    mkdir -p "$(dirname "$BLOCK_DEST")"
    cp -r "$BLOCK_SRC" "$BLOCK_DEST"
    echo "  - Recently accessed courses block installed to $BLOCK_DEST"
fi

# Course progress overview block
if [ -d "$PROGRESS_SRC" ]; then
    mkdir -p "$(dirname "$PROGRESS_DEST")"
    cp -r "$PROGRESS_SRC" "$PROGRESS_DEST"
    echo "  - Course progress overview block installed to $PROGRESS_DEST"
fi

echo ""
echo "Done! Next steps:"
echo "  1. Visit Site administration -> Notifications to upgrade"
echo "  2. Go to Appearance -> Themes and select Edumasr Academy"
echo "  3. Configure colors at Appearance -> Themes -> Edumasr Academy"
echo "  4. Add blocks to Dashboard: 'Recently accessed courses', 'Course progress overview' (Turn editing on -> Add block)"
echo "  5. Purge all caches"
echo ""
echo "If you get permission errors, run: sudo ./install.sh $MOODLE_ROOT"
