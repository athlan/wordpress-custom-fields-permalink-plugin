<?php
/**
 * Register hooks in WordPress
 *
 * @package WordPress_Custom_Fields_Permalink
 */

// Require main class.
require 'class-wp-post-meta.php';
require 'class-wp-permalink.php';
require 'class-wp-request-processor.php';
require 'class-wp-rewrite-rules.php';
require 'class-plugin-updater.php';
require 'class-field-attributes.php';

use CustomFieldsPermalink\Plugin_Updater;
use CustomFieldsPermalink\WP_Permalink;
use CustomFieldsPermalink\WP_Post_Meta;
use CustomFieldsPermalink\WP_Request_Processor;
use CustomFieldsPermalink\WP_Rewrite_Rules;
use CustomFieldsPermalink\Field_Attributes;

$post_meta        = new WP_Post_Meta();
$field_attributes = new Field_Attributes();

// Permalink generation.
$permalink = new WP_Permalink( $post_meta, $field_attributes );
add_filter( 'pre_post_link', array( $permalink, 'link_post' ), 100, 3 );
add_filter( 'post_type_link', array( $permalink, 'link_post_type' ), 100, 4 );

// Request processing.
$request_processor = new WP_Request_Processor( $post_meta );
add_filter( 'query_vars', array( $request_processor, 'register_extra_query_vars' ), 10, 1 );
add_filter( 'request', array( $request_processor, 'process_request' ), 10, 1 );
add_filter( 'pre_handle_404', array( $request_processor, 'pre_handle_404' ), 10, 2 );

// Manage rewrite rules.
$rules_rewriter = new WP_Rewrite_Rules( $field_attributes );
add_filter( 'rewrite_rules_array', array( $rules_rewriter, 'rewrite_rules_array_filter' ) );
add_filter( 'pre_update_option_permalink_structure', array( $rules_rewriter, 'permalink_structure_option_filter' ) );

// Manage plugin updates.
$plugin_updater = new Plugin_Updater();
add_action( 'init', array( $plugin_updater, 'on_init_hook' ) );
