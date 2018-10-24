=== Custom Fields Permalink 2 ===
Contributors: athlan
Donate link: http://athlan.pl/wordpres-custom-fields-permalink-plugin/
Tags: custom fields, permalinks, permalink, url, custom post types, post type, tax, taxonomy, types
Requires at least: 4.5.0
Tested up to: 5.0
Stable tag: 1.5.0
Requires PHP: 5.3
License: MIT
License URI: http://opensource.org/licenses/MIT

Plugin allows to use post's custom fields values in permalink structure by adding %field_fieldname%, for posts, pages and custom post types.

== Description ==

Plugin allows to use post's custom fields values in permalink structure by adding `%field_fieldname%` rewrite tag.

Examples:

* `http://example.com/%field_event_date_from%/%postname%/`
* `http://example.com/post-type/%field_event_date_from%/%postname%/` (with <a href="https://wordpress.org/plugins/custom-post-type-permalinks/">Custom Post Type Permalinks</a> plugin)

You can also set different permalink structure depending on custom post type using <a href="https://wordpress.org/plugins/custom-post-type-permalinks/">Custom Post Type Permalinks</a> plugin. You can create own post types by using <a href="https://wordpress.org/plugins/custom-post-type-ui/">Custom Post Type UI</a> plugin.

The plugin works for:

* posts
* pages
* custom post types

Plugin is also available on GitHub:
<a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin">https://github.com/athlan/wordpress-custom-fields-permalink-plugin</a>

== Installation ==

* Install plugin in WordPress system in Plugins section. You can search for "Custom Fields Permalink 2".
* Done! Now you can use `%field_fieldname%` tag in Settings -> Permalinks.

== Frequently Asked Questions ==

= Found the bug. How to raise a ticket? =

The best way is to raise the ticket under the GitHub repository:
<a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/issues/new">https://github.com/athlan/wordpress-custom-fields-permalink-plugin/issues/new</a>

= I want to make a contribution =

We would be very grateful in any contribution. If you have a idea for the feature, please discuss it first by
<a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/issues/new">raising the ticket</a>.
When the assumptions are ready, please
<a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/pulls">make a pull request</a> at GitHub.

= How to generate missing custom post meta keys and values =

In case of missing custom post field values you can generate them on-the-fly using <a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/wiki/Plugin-hooks#generate_dynamic_metadata"><code>generate_dynamic_metadata</code></a> filter.

Read <a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/wiki/FAQ#how-to-generate-missing-custom-post-meta-keys-and-values">the example</a>.

= How to generate calculated dynamic custom post meta keys and values =

You can generate custom post fields dynamically coding some logic using  <a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/wiki/Plugin-hooks#generate_dynamic_metadata"><code>generate_dynamic_metadata</code></a> filter.

Read <a href="https://github.com/athlan/wordpress-custom-fields-permalink-plugin/wiki/FAQ#how-to-generate-calculated-dynamic-custom-post-meta-keys-and-values">the example</a>.

== Screenshots ==

1. Pemralink settings
2. Custom post types Pemralink settings

== Changelog ==

Release notes: https://github.com/athlan/wordpress-custom-fields-permalink-plugin/releases

== Upgrade Notice ==

No upgrade notices.
