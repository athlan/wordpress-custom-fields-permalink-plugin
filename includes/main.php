<?php
/**
 * Register hooks in WordPress
 *
 * @package WordPress_Custom_Fields_Permalink
 */

// Require main class.
require 'class-customfieldspermalink.php';

add_filter( 'pre_post_link', array( 'CustomFieldsPermalink', 'link_post' ), 100, 3 );
add_filter( 'post_type_link', array( 'CustomFieldsPermalink', 'link_post_type' ), 100, 4 );
add_filter( 'rewrite_rules_array', array( 'CustomFieldsPermalink', 'rewrite_rules_array_filter' ) );
add_filter( 'query_vars', array( 'CustomFieldsPermalink', 'register_extra_query_vars' ), 10, 1 );
add_filter( 'request', array( 'CustomFieldsPermalink', 'process_request' ), 10, 1 );
add_filter( 'pre_handle_404', array( 'CustomFieldsPermalink', 'pre_handle_404' ), 10, 2 );
