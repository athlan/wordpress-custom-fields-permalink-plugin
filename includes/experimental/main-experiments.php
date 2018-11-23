<?php
/**
 * Register hooks in WordPress
 *
 * @package WordPress_Custom_Fields_Permalink
 */

// Require main class.
$experiments = apply_filters( 'wpcfp_experiments', array() );

if ( array_search( 'chain_rewrite', $experiments ) !== false ) {
	include 'main-experiment-chain-rewrite-rules.php';
}
