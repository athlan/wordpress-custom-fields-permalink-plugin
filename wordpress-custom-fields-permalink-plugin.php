<?php
/**
 * Custom Fields Permalink 2.
 *
 * @package WordPress_Custom_Fields_Permalink
 *
 * @wordpress-plugin
 * Plugin Name: Custom Fields Permalink 2
 * Plugin URI: http://athlan.pl/wordpress-custom-fields-permalink-plugin
 * Description: Plugin allows to use post's custom fields values in permalink structure by adding %field_fieldname%, for posts, pages and custom post types.
 * Author: Piotr Pelczar
 * Version: 1.1.0
 * Author URI: http://athlan.pl/
 */

// Require main class.
require 'includes/class-customfieldspermalink.php';

add_filter( 'pre_post_link', array( 'CustomFieldsPermalink', 'link_post' ), 100, 3 );
add_filter( 'post_type_link', array( 'CustomFieldsPermalink', 'link_post_type' ), 100, 4 );
add_filter( 'rewrite_rules_array', array( 'CustomFieldsPermalink', 'rewrite_rules_array_filter' ) );
add_filter( 'query_vars', array( 'CustomFieldsPermalink', 'register_extra_query_vars' ), 10, 1 );
add_filter( 'request', array( 'CustomFieldsPermalink', 'process_request' ), 10, 1 );
add_filter( 'pre_handle_404', array( 'CustomFieldsPermalink', 'pre_handle_404' ), 10, 2 );
