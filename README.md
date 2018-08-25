# Custom Fields Permalink Redux

Plugin allows to use post's custom fields values in permalink structure by adding %field_fieldname%, for posts, pages and custom post types.

[![Build Status](https://travis-ci.org/athlan/wordpress-custom-fields-permalink-plugin.svg?branch=master)](https://travis-ci.org/athlan/wordpress-custom-fields-permalink-plugin)
[![codecov](https://codecov.io/gh/athlan/wordpress-custom-fields-permalink-plugin/branch/master/graph/badge.svg)](https://codecov.io/gh/athlan/wordpress-custom-fields-permalink-plugin)

## Description

Plugin allows to use post's custom fields values in permalink structure by adding `%field_fieldname%` rewrite tag.

![Screenshot](https://raw.githubusercontent.com/athlan/wordpress-custom-fields-permalink-plugin/master/assets/screenshot-1.png "Screenshot")

Examples:

* `http://example.com/%field_event_date_from%/%postname%/`
* `http://example.com/post-type/%field_event_date_from%/%postname%/` (with <a href="https://wordpress.org/plugins/custom-post-type-permalinks/">Custom Post Type Permalinks</a> plugin)

You can also set different permalink structure depending on custom post type using <a href="https://wordpress.org/plugins/custom-post-type-permalinks/">Custom Post Type Permalinks</a> plugin. You can create own post types by using <a href="https://wordpress.org/plugins/custom-post-type-ui/">Custom Post Type UI</a> plugin.

The plugin works for:

* posts
* pages
* custom post types

Plugin is also avaliable on GitHub:
<a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin">https://github.com/athlan/wordpress-custom-fields-permalink-plugin</a>

## Installation

Search for **Custom Fields Permalink 2** or follow the link
https://wordpress.org/plugins/custom-fields-permalink-redux/

## Extensions

### Advanced Cutom Fields

The extension of this plugin to fully support ACF plugin is availiable:

https://github.com/athlan/acf-permalink

## Changelog

Release notes: https://github.com/athlan/wordpress-custom-fields-permalink-plugin/releases
