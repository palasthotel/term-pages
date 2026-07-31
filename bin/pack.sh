#!/bin/sh
# Stages the plugin in build/term-pages/ — this directory is the exact payload
# that gets deployed to the WordPress.org SVN trunk — and zips it to
# term-pages.zip in the project root.
#
# When you add a file that belongs into the released plugin, add it to
# PLUGIN_FILES below. Everything not listed here stays GitHub-only.
set -e

PLUGIN_SLUG="term-pages"
PLUGIN_FILES="term-pages.php admin.js readme.txt screenshot-1.png languages"

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
PROJECT_PATH=$(cd "$SCRIPT_DIR/.." && pwd)
BUILD_PATH="$PROJECT_PATH/build"
DEST_PATH="$BUILD_PATH/$PLUGIN_SLUG"

echo "Generating build directory..."
rm -rf "$BUILD_PATH"
mkdir -p "$DEST_PATH"

echo "Syncing files..."
for item in $PLUGIN_FILES; do
	cp -R "$PROJECT_PATH/$item" "$DEST_PATH/"
done

echo "Generating zip file..."
cd "$BUILD_PATH" || exit 1
zip -q -r "${PLUGIN_SLUG}.zip" "$PLUGIN_SLUG/"
mv "${PLUGIN_SLUG}.zip" "$PROJECT_PATH/"

cd "$PROJECT_PATH" || exit 1
echo "${PLUGIN_SLUG}.zip file generated!"
echo "Plugin payload staged in build/${PLUGIN_SLUG}/"
echo "Build done!"
