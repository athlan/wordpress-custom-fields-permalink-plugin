#!/bin/bash

usage() {
    echo "usage: $0 <version-type> <version-to-set>"
	echo ""
	echo "version-type: plugin_version|wp_min|wp_to|php_min"
	echo ""
}

if [ $# -lt 2 ]; then
	usage
	exit 1
fi

version_type="handle_$1"
version_to_bump="$2"

file_plugin_main="wordpress-custom-fields-permalink-plugin.php"
file_readme_repo="README.md"
file_readme_wp="readme.txt"
file_travisci=".travis.yml"

handle_plugin_version() {
    echo "Starting bumping plugin version to $version_to_bump"

    sed -i -E "s/Version: (.+)/Version: $version_to_bump/" "$file_plugin_main"
    echo "Version bumped in $file_plugin_main"

    sed -i -E "s/Stable tag: (.+)/Stable tag: $version_to_bump/" "$file_readme_repo"
    echo "Version bumped in $file_readme_repo"

    sed -i -E "s/Stable tag: (.+)/Stable tag: $version_to_bump/" "$file_readme_wp"
    echo "Version bumped in $file_readme_wp"
}

handle_wp_min() {
    echo "Starting bumping minimum Word Press version to $version_to_bump"

    sed -i -E "s/Requires at least: (.+)/Requires at least: $version_to_bump/" "$file_readme_repo"
    echo "Version bumped in $file_readme_repo"

    sed -i -E "s/Requires at least: (.+)/Requires at least: $version_to_bump/" "$file_readme_wp"
    echo "Version bumped in $file_readme_wp"
}

handle_wp_to() {
    echo "Starting bumping tested up to Word Press version to $version_to_bump"

    sed -i -E "s/Tested up to: (.+)/Tested up to: $version_to_bump/" "$file_readme_repo"
    echo "Version bumped in $file_readme_repo"

    sed -i -E "s/Tested up to: (.+)/Tested up to: $version_to_bump/" "$file_readme_wp"
    echo "Version bumped in $file_readme_wp"
}

handle_php_min() {
    echo "Starting bumping php min version to $version_to_bump"

    sed -i -E "s/Requires PHP: (.+)/Requires PHP: $version_to_bump/" "$file_readme_repo"
    echo "Version bumped in $file_readme_repo"

    sed -i -E "s/Requires PHP: (.+)/Requires PHP: $version_to_bump/" "$file_readme_wp"
    echo "Version bumped in $file_readme_wp"
}

if [ $(type -t $version_type) == function ]; then
    $version_type
else
    usage
    exit 1
fi
