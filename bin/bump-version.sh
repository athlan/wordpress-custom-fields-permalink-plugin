#!/bin/bash

usage() {
    echo "usage: $0 <version-type> <version-to-set>"
	echo ""
	echo "version-type: plugin_version"
	echo ""
}

if [ $# -lt 2 ]; then
	usage
	exit 1
fi

version_type="handle_$1"
version_to_bump="$2"

handle_plugin_version() {
    echo "123";
}

if [ $(type -t "$version_type") == function ]; then
    $version_type
else
    usage
    exit 1
fi
