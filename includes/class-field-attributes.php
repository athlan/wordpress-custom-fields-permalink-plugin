<?php
/**
 * Class Field_Attr
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

/**
 * Class Field_Attr parses attributes string.
 */
class Field_Attributes {

	/**
	 * Extract the metadata value from the post.
	 *
	 * @param string $string The attributes string.
	 *
	 * @return array
	 */
	public function parse_attributes( $string ) {
		if ( null === $string || '' === trim( $string ) ) {
			return array();
		}

		$result = array();

		$parsed = shortcode_parse_atts( $string );
		foreach ( $parsed as $key => $value ) {
			if ( is_integer( $key ) ) {
				$result[ $value ] = true;
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}
}
