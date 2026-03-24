#!/usr/bin/env bash
# Copy custom My Courses plugin files from theme repo to Moodle public local_edumasrdashboard.
# Run from repo root: ./sync-courses-to-public.sh
# If you get "Permission denied", run: sudo ./sync-courses-to-public.sh

set -e

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_SRC="${REPO_ROOT}/local/edumasrdashboard"
PLUGIN_DEST="/Users/marwansalem/sites/moodle/public/local/edumasrdashboard"

if [[ ! -d "$PLUGIN_SRC" ]]; then
    echo "Error: Plugin source not found: $PLUGIN_SRC"
    exit 1
fi

if [[ ! -d "$(dirname "$PLUGIN_DEST")" ]]; then
    echo "Error: Moodle local directory not found: $(dirname "$PLUGIN_DEST")"
    exit 1
fi

echo "Copying My Courses plugin from: $PLUGIN_SRC"
echo "                             to: $PLUGIN_DEST"
echo ""

# Copy entire plugin directory (creates subdirs, overwrites files)
COPY_FAILED=0
if command -v rsync &> /dev/null; then
    rsync -a --no-times "$PLUGIN_SRC/" "$PLUGIN_DEST/" || COPY_FAILED=1
else
    mkdir -p "$PLUGIN_DEST/classes/output" "$PLUGIN_DEST/templates" "$PLUGIN_DEST/lang/en"
    cp -f "$PLUGIN_SRC/courses.php" "$PLUGIN_DEST/" && \
    cp -f "$PLUGIN_SRC/classes/output/"*.php "$PLUGIN_DEST/classes/output/" && \
    cp -f "$PLUGIN_SRC/templates/"*.mustache "$PLUGIN_DEST/templates/" && \
    cp -f "$PLUGIN_SRC/lang/en/"*.php "$PLUGIN_DEST/lang/en/" || COPY_FAILED=1
fi

if [[ "$COPY_FAILED" -ne 0 ]]; then
    echo "Permission denied. Run with sudo:"
    echo "  sudo $REPO_ROOT/sync-courses-to-public.sh"
    echo ""
    echo "Or fix ownership:  sudo chown -R \$(whoami) $PLUGIN_DEST"
    exit 1
fi

echo "Done. My Courses plugin synced to public."
echo "Purge Moodle caches and open My courses from the sidebar."
