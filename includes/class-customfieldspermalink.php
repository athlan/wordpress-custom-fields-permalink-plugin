<?php
/**
 * Class CustomFieldsPermalink
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class CustomFieldsPermalink provides the implementation of custom post fields in permalinks.
 */
class CustomFieldsPermalink {

	const PARAM_CUSTOMFIELD_KEY   = 'custom_field_key';
	const PARAM_CUSTOMFIELD_VALUE = 'custom_field_value';

	/**
	 * Filters the permalink structure for a post before token replacement occurs..
	 * The pre_post_link filter implementation.
	 *
	 * @param string  $permalink  The site's permalink structure.
	 * @param WP_Post $post       The post in question.
	 * @param bool    $leavename  Whether to keep the post name.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_post_link/
	 *
	 * @return mixed
	 */
	public static function link_post( $permalink, $post, $leavename ) {
		return self::link_rewrite_fields( $permalink, $post );
	}

	/**
	 * Filters the permalink for a post of a custom post type.
	 * The post_type_link filter implementation.
	 *
	 * @param string  $permalink  The post's permalink.
	 * @param WP_Post $post       The post in question.
	 * @param bool    $leavename  Whether to keep the post name.
	 * @param bool    $sample     Is it a sample permalink.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/post_type_link/
	 *
	 * @return mixed
	 */
	public static function link_post_type( $permalink, $post, $leavename, $sample ) {
		return self::link_rewrite_fields( $permalink, $post );
	}

	/**
	 * Rewrites permalink replacing custom fields.
	 *
	 * @param string  $permalink The permalink.
	 * @param WP_Post $post      The post.
	 *
	 * @return string
	 */
	private static function link_rewrite_fields( $permalink, $post ) {
		$replace_callback = function ( $matches ) use ( &$post ) {
			return CustomFieldsPermalink::link_rewrite_fields_extract( $post, $matches[2] );
		};

		return preg_replace_callback( '#(%field_(.*?)%)#', $replace_callback, $permalink );
	}

	/**
	 * Extract the metadata value from the post.
	 *
	 * @param WP_Post $post       The post.
	 * @param string  $field_name The metadata key to extract.
	 *
	 * @return string
	 */
	public static function link_rewrite_fields_extract( $post, $field_name ) {
		$post_meta = self::get_post_meta( $post );

		if ( ! isset( $post_meta[ $field_name ] ) ) {
			return '';
		}

		$value = $post_meta[ $field_name ][0];

		$value = sanitize_title( $value );

		return $value;
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
	public static function register_extra_query_vars( $public_query_vars ) {
		array_push( $public_query_vars, self::PARAM_CUSTOMFIELD_KEY, self::PARAM_CUSTOMFIELD_VALUE );

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
	public static function process_request( $query_vars ) {
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
	public static function pre_handle_404( $preempt, $wp_query ) {
		// Analyse only if there is post parsed.
		if ( ! is_single() ) {
			return false;
		}

		$post = $wp_query->post;

		// Analyse only if custom field used in query.
		if ( ! array_key_exists( self::PARAM_CUSTOMFIELD_KEY, $wp_query->query_vars ) ) {
			return false;
		}

		$raise_404 = false;

		$post_meta = self::get_post_meta( $post );

		$query_meta_key = $wp_query->query_vars[ self::PARAM_CUSTOMFIELD_KEY ];

		if ( ! array_key_exists( $query_meta_key, $post_meta ) ) {
			$raise_404 = true;
		} else {
			$query_meta_value = $wp_query->query_vars[ self::PARAM_CUSTOMFIELD_VALUE ];

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

		if ( $raise_404 ) {
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();

			// 404 already raised, break the circuit.
			return true;
		}

		return false;
	}

	/**
	 * Filters the full set of generated rewrite rules.
	 * The rewrite_rules_array filter implementation.
	 *
	 * @param array $rules The compiled array of rewrite rules.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/rewrite_rules_array/
	 *
	 * @return array
	 */
	public static function rewrite_rules_array_filter( $rules ) {
		$keys  = array_keys( $rules );
		$tmp   = $rules;
		$rules = array();

		$j = sizeof( $keys );
		for ( $i = 0; $i < $j; ++ $i ) {
			$key = $keys[ $i ];

			if ( preg_match( '/%field_([^%]*?)%/', $key ) ) {
				$key_new = preg_replace(
					'/%field_([^%]*?)%/',
					'([^/]+)',
					// You can simply add next group to the url, because WordPress.
					// Detect them automatically and add next $matches indices.
					$key
				);
				$rules[ $key_new ] = preg_replace(
					'/%field_([^%]*?)%/',
					sprintf( '%s=$1&%s=', self::PARAM_CUSTOMFIELD_KEY, self::PARAM_CUSTOMFIELD_VALUE ),
					// Here on the end will be pasted $matches[$i] from $keyNew,
					// so we can grab it it the future in self::PARAM_CUSTOMFIELD_VALUE parameter.
					$tmp[ $key ]
				);
			} else {
				$rules[ $key ] = $tmp[ $key ];
			}
		}

		return $rules;
	}

	/**
	 * Get post meta applying <code>wpcfp_get_post_metadata</code> filter.
	 *
	 * @param WP_Post $post The post.
	 *
	 * @return array
	 */
	private static function get_post_meta( $post ) {
		$post_meta = get_post_meta( $post->ID );

		/**
		 * Filters of retrieved metadata of a post to link rewrite.
		 *
		 * @since 1.2.0
		 *
		 * @param array   $post_meta  The metadata returned from get_post_meta.
		 * @param WP_Post $post       The post object.
		 */
		$filtered_post_meta = apply_filters( 'wpcfp_get_post_metadata', $post_meta, $post );

		// Do some fixes after user generated values.
		// If it's single value, wrap this in array, as WordPress internally does.
		// @see get_post_meta() with $single = false.
		foreach ( $filtered_post_meta as $key => &$value ) {
			if ( ! is_array( $value ) ) {
				$value = array( $value );
			}
		}

		return $filtered_post_meta;
	}
}
