<?php

class CustomFieldsPermalink {

	const PARAM_CUSTOMFIELD_KEY   = 'custom_field_key';
	const PARAM_CUSTOMFIELD_VALUE = 'custom_field_value';

	private static $check_custom_field_value = false;

	public static function link_post( $permalink, $post, $leavename ) {
		return self::link_rewrite_fields( $permalink, $post );
	}

	public static function link_post_type( $permalink, $post, $leavename, $sample ) {
		return self::link_rewrite_fields( $permalink, $post );
	}

	protected static function link_rewrite_fields( $permalink, $post ) {
		$replace_callback = function ( $matches ) use ( &$post ) {
			return CustomFieldsPermalink::link_rewrite_fields_extract( $post, $matches[2] );
		};

		return preg_replace_callback( '#(%field_(.*?)%)#', $replace_callback, $permalink );
	}

	public static function link_rewrite_fields_extract( $post, $field_name ) {
		$post_meta = get_post_meta( $post->ID );

		if ( ! isset( $post_meta[ $field_name ] ) ) {
			return '';
		}

		$value = $post_meta[ $field_name ][0];

		$value = sanitize_title( $value );

		return $value;
	}

	public static function register_extra_query_vars( $value ) {
		array_push( $value, self::PARAM_CUSTOMFIELD_KEY, self::PARAM_CUSTOMFIELD_VALUE );

		return $value;
	}

	public static function process_request( $value ) {
		// Additional parameters added to WordPress.
		// Main Loop query.
		if ( array_key_exists( self::PARAM_CUSTOMFIELD_KEY, $value ) ) {
			$value['meta_key'] = $value[ self::PARAM_CUSTOMFIELD_KEY ];

			// Remove temporary injected parameter.
			unset( $value[ self::PARAM_CUSTOMFIELD_KEY ] );

			// Do not check field's value for this moment.
			if ( true === self::$check_custom_field_value ) {
				if ( array_key_exists( self::PARAM_CUSTOMFIELD_VALUE, $value ) ) {
					$value['meta_value'] = $value[ self::PARAM_CUSTOMFIELD_VALUE ];

					// Remove temporary injected parameter.
					unset( $value[ self::PARAM_CUSTOMFIELD_VALUE ] );
				}
			}
		}

		return $value;
	}

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
