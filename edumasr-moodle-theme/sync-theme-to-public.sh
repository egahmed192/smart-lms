#!/usr/bin/env bash
# Copy theme from edumasr-moodle-theme repo into Moodle public theme folder.
# Run from repo root: ./sync-theme-to-public.sh
# If you get "Permission denied", run: sudo ./sync-theme-to-public.sh

set -e

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
THEME_SRC="${REPO_ROOT}/theme/edumasr"
THEME_DEST="/Users/marwansalem/sites/moodle/public/theme/edumasr"

if [[ ! -d "$THEME_SRC" ]]; then
    echo "Error: Theme source not found: $THEME_SRC"
    exit 1
fi

if [[ ! -d "$(dirname "$THEME_DEST")" ]]; then
    echo "Error: Moodle theme directory not found: $(dirname "$THEME_DEST")"
    exit 1
fi

# Check write access (avoid partial copy then failure)
if ! touch "$THEME_DEST/.sync-write-test" 2>/dev/null; then
    echo "No write access to: $THEME_DEST"
    echo ""
    echo "Run with sudo:  sudo $REPO_ROOT/sync-theme-to-public.sh"
    echo ""
    echo "Or fix ownership (e.g. so your user can write):"
    echo "  sudo chown -R \$(whoami) $THEME_DEST"
    exit 1
fi
rm -f "$THEME_DEST/.sync-write-test"

echo "Copying theme from: $THEME_SRC"
echo "                 to: $THEME_DEST"
echo ""

# Copy entire theme. --no-times avoids 'failed to set times' when dest has restricted permissions.
SYNC_EXIT=0
if command -v rsync &> /dev/null; then
    set +e
    rsync -a --no-times "$THEME_SRC/" "$THEME_DEST/"
    SYNC_EXIT=$?
    set -e
else
    cp -R "$THEME_SRC"/* "$THEME_DEST/"
fi

if [[ $SYNC_EXIT -ne 0 ]]; then
    echo ""
    echo "rsync failed (exit $SYNC_EXIT). If you saw 'Permission denied', run:"
    echo "  sudo $REPO_ROOT/sync-theme-to-public.sh"
    exit $SYNC_EXIT
fi

echo "Done. Theme synced to public."
