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
	 * Do check against meta value or not.
	 *
	 * @var bool
	 */
	private static $check_custom_field_value = false;

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
	private static function link_rewrite_fields_extract( $post, $field_name ) {
		$post_meta = get_post_meta( $post->ID );

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
		if ( array_key_exists( self::PARAM_CUSTOMFIELD_KEY, $query_vars ) ) {
			$query_vars['meta_key'] = $query_vars[ self::PARAM_CUSTOMFIELD_KEY ];

			// Remove temporary injected parameter.
			unset( $query_vars[ self::PARAM_CUSTOMFIELD_KEY ] );

			// Do not check field's value for this moment.
			if ( true === self::$check_custom_field_value ) {
				if ( array_key_exists( self::PARAM_CUSTOMFIELD_VALUE, $query_vars ) ) {
					$query_vars['meta_value'] = $query_vars[ self::PARAM_CUSTOMFIELD_VALUE ];

					// Remove temporary injected parameter.
					unset( $query_vars[ self::PARAM_CUSTOMFIELD_VALUE ] );
				}
			}
		}

		return $query_vars;
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
}
