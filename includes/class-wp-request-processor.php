<?php
/**
 * Class RequestProcessor
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

use WP_Query;

/**
 * Class WP_Request_Processor drives the process of using custom post fields in request.
 */
class WP_Request_Processor {

	const PARAM_CUSTOMFIELD_PARAMES = 'custom_field_params';

	/**
	 * Post meta provider.
	 *
	 * @var WP_Post_Meta
	 */
	private $post_meta;

	/**
	 * WP_Permalink constructor.
	 *
	 * @param WP_Post_Meta $post_meta Post meta provider.
	 */
	public function __construct( WP_Post_Meta $post_meta ) {
		$this->post_meta = $post_meta;
	}

	/**
	 * Filters the query variables whitelist before processing.
	 * The query_vars filter implementation.
	 *
	 * @param array $public_query_vars The array of whitelisted query variables.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 *
	 * @return mixed
	 */
	public function register_extra_query_vars( $public_query_vars ) {
		array_push( $public_query_vars, self::PARAM_CUSTOMFIELD_PARAMES );

		return $public_query_vars;
	}

	/**
	 * Filters the array of parsed query variables.
	 * The request filter implementation.
	 *
	 * @param array $query_vars The array of requested query variables.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/request/
	 *
	 * @return mixed
	 */
	public function process_request( $query_vars ) {
		// Additional parameters added to WordPress.
		// Main Loop query.
		return $query_vars;
	}

	/**
	 * Filters whether to short-circuit default header status handling.
	 *
	 * Raises 404 if post has been rewrited, but:
	 * 1. Custom field key does not exists or
	 * 2. Custom field value does not matches.
	 *
	 * @param bool     $preempt  Whether to short-circuit default header status handling. Default false.
	 * @param WP_Query $wp_query WordPress Query object.
	 *
	 * @return bool Returning a non-false value from the filter will short-circuit the handling
	 * and return early.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_handle_404/
	 */
	public function pre_handle_404( $preempt, $wp_query ) {
		// Analyse only if there is post parsed.
		if ( ! is_single() ) {
			return false;
		}

		$post = $wp_query->post;

		// Analyse only if custom field used in query.
		if ( ! array_key_exists( self::PARAM_CUSTOMFIELD_PARAMES, $wp_query->query_vars )
			|| ! is_array( $wp_query->query_vars[ self::PARAM_CUSTOMFIELD_PARAMES ] ) ) {
			return false;
		}

		$query_meta_params = $wp_query->query_vars[ self::PARAM_CUSTOMFIELD_PARAMES ];

		$raise_404 = false;

		$post_meta = $this->post_meta->get_post_meta( $post );

		foreach ( $query_meta_params as $query_meta_key => $query_meta_value ) {
			if ( ! array_key_exists( $query_meta_key, $post_meta ) ) {
				$raise_404 = true;
				break;
			} else {
				// Look for at least one value match.
				$value_matched = false;
				foreach ( $post_meta[ $query_meta_key ] as $post_meta_value ) {
					$post_meta_value_sanitized = sanitize_title( $post_meta_value );

					if ( $query_meta_value == $post_meta_value_sanitized ) {
						$value_matched = true;
						break;
					}
				}

				if ( ! $value_matched ) {
					$raise_404 = true;
				}
			}
		}

		if ( $raise_404 ) {
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();

			// 404 already raised, break the circuit.
			return true;
		}

		return false;
	}
}
