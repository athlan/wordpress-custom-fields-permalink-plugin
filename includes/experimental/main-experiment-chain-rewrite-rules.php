<?php
/**
 * Register hooks in WordPress
 *
 * @package WordPress_Custom_Fields_Permalink
 */

// Require main class.
require_once 'class-wp-request-processor-rewrite-chain.php';

use CustomFieldsPermalink\WP_Request_Processor_Rewrite_Chain;

// Request processing.
$request_processor = new WP_Request_Processor_Rewrite_Chain();
add_filter( 'pre_handle_404', array( $request_processor, 'pre_handle_404' ), 5, 2 );
add_filter( 'pre_option_rewrite_rules', array( $request_processor, 'pre_option_rewrite_rules' ) );
add_filter( 'option_rewrite_rules', array( $request_processor, 'option_rewrite_rules' ) );
