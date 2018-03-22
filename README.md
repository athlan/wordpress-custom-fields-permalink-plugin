# Custom Fields Permalink Redux

Plugin allows to use post's custom fields values in permalink structure by adding %field_fieldname%, for posts, pages and custom post types.

![Build Status](https://travis-ci.org/athlan/wordpress-custom-fields-permalink-plugin.svg?branch=master)

---

* Contributors: <a href="https://github.com/athlan">athlan</a>
* Plugin url: [http://athlan.pl/wordpres-custom-fields-permalink-plugin/](http://athlan.pl/wordpres-custom-fields-permalink-plugin/)
* Tags: custom fields, permalinks, permalink, url, custom post types, post type, tax, taxonomy, types
* Requires at least: 3.0.0
* Tested up to: 4.9.3
* Stable tag: 1.0.2
* License: MIT
* License URI: http://opensource.org/licenses/MIT

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

* Download the wordpress-custom-fields-permalink-plugin.zip file to your computer.
* Unzip the file.
* Upload the `wordpress-custom-fields-permalink-plugin` directory to your `/wp-content/plugins/` directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* Now you can use `%field_fieldname%` tag in Settings -> Permalinks.

## Changelog

Release notes: https://github.com/athlan/wordpress-custom-fields-permalink-plugin/releases

## Upgrade Notice

No upgrade notices.
