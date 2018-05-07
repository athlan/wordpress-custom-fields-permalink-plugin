<?php
/**
 * Class RulesRewriter
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

/**
 * Class Rewrite_Rules handles the process or creating rewrite rules.
 */
class WP_Rewrite_Rules {

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
		$new_rules = array();

		foreach ( $rules as $key => $rule ) {
			if ( preg_match( '/%field_([^%]*?)%/', $key ) ) {
				$key_new = preg_replace(
					'/%field_([^%]*?)%/',
					'([^/]+)',
					// You can simply add next group to the url, because WordPress.
					// Detect them automatically and add next $matches indices.
					$key
				);
				$new_rules[ $key_new ] = preg_replace(
					'/%field_([^%]*?)%/',
					sprintf( '%s[$1]=', WP_Request_Processor::PARAM_CUSTOMFIELD_PARAMES ),
					// Here on the end will be pasted $matches[$i] from $keyNew,
					// so we can grab it it the future in self::PARAM_CUSTOMFIELD_VALUE parameter.
					$rule
				);
			} else {
				$new_rules[ $key ] = $rule;
			}
		}

		return $new_rules;
	}
}
