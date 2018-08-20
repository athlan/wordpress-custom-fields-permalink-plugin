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
	 * The field extraction regexp.
	 *
	 * Samples:
	 * 1) /permalink/%field_one%/
	 * 2) /permalink/%field_one(attr1 attr2)%/
	 * 3) /permalink/%field_one (attr1 attr2)%/
	 *
	 * Groups:
	 * 1. Field name
	 * 2. Existence of attributes section
	 * 3. Attributes section
	 */
	const FIELD_REGEXP = '%field_([^%]*?)(\s*?\((.*)\))?%';

	const FIELD_REGEXP_MAIN_GROUP       = 0;
	const FIELD_REGEXP_NAME_GROUP       = 1;
	const FIELD_REGEXP_ATTRIBUTES_GROUP = 3;

	/**
	 * Field attributes parser.
	 *
	 * @var Field_Attributes
	 */
	private $field_attributes;

	/**
	 * WP_Rewrite_Rules constructor.
	 *
	 * @param Field_Attributes $field_attributes Field attributes parser.
	 */
	public function __construct( Field_Attributes $field_attributes ) {
		$this->field_attributes = $field_attributes;
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
	public function rewrite_rules_array_filter( $rules ) {
		$new_rules = array();

		foreach ( $rules as $key => $rule ) {
			if ( preg_match_all( '/' . self::FIELD_REGEXP . '/', $key, $key_matches ) ) {
				$key_new = preg_replace(
					'/' . self::FIELD_REGEXP . '/',
					'([^/]+)',
					// You can simply add next group to the url, because WordPress.
					// Detect them automatically and add next $matches indices.
					$key
				);

				$new_rule = $rule;
				foreach ( $key_matches[ self::FIELD_REGEXP_MAIN_GROUP ] as $i => $key_match ) {
					$new_rule_replacement = $this->build_rule_on_field_match( $key_matches, $i );

					$new_rule = str_replace(
						$key_match,
						$new_rule_replacement,
						// Here on the end will be pasted $matches[$i] from $keyNew,
						// so we can grab it it the future in self::PARAM_CUSTOMFIELD_VALUE parameter.
						$new_rule
					);
				}

				$new_rules[ $key_new ] = $new_rule;
			} else {
				$new_rules[ $key ] = $rule;
			}
		}

		return $new_rules;
	}

	/**
	 * Fixes the permalink structure option which encodes as url the field definition.
	 *
	 * @param mixed $new_value The new value.
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/pre_update_option_(option_name)
	 * @see WP_Rewrite::set_permalink_structure()
	 *
	 * @return mixed
	 */
	public function permalink_structure_option_filter( $new_value ) {
		preg_match( '/' . self::FIELD_REGEXP . '/', $new_value, $matches );

		if ( isset( $matches[ self::FIELD_REGEXP_ATTRIBUTES_GROUP ] ) ) {
			$new_value_filtered = str_replace( $matches[ self::FIELD_REGEXP_ATTRIBUTES_GROUP ], urldecode( $matches[ self::FIELD_REGEXP_ATTRIBUTES_GROUP ] ), $new_value );
			return $new_value_filtered;
		} else {
			return $new_value;
		}
	}

	/**
	 * Builds the part of rewrite rule replacement based on parameter match and its attributes.
	 *
	 * @param array   $key_matches All found matches.
	 * @param integer $i Match number.
	 *
	 * @access private
	 * @return string The rewrite rule replacement.
	 */
	private function build_rule_on_field_match( $key_matches, $i ) {
		$field_name       = $key_matches[ self::FIELD_REGEXP_NAME_GROUP ][ $i ];
		$field_attributes = $this->field_attributes->parse_attributes( $key_matches[ self::FIELD_REGEXP_ATTRIBUTES_GROUP ][ $i ] );

		$rule_rewrite = array();
		$rule_rewrite[ WP_Request_Processor::PARAM_CUSTOMFIELD_PARAMS_ATTR ] = array();
		$rule_rewrite[ WP_Request_Processor::PARAM_CUSTOMFIELD_PARAMS ]      = array();

		if ( $field_attributes ) {
			foreach ( $field_attributes as $attr_key => $attr_value ) {
				$rule_rewrite[ WP_Request_Processor::PARAM_CUSTOMFIELD_PARAMS_ATTR ][ $field_name . '::' . $attr_key ] = $attr_value;
			}
		}

		$rule_rewrite[ WP_Request_Processor::PARAM_CUSTOMFIELD_PARAMS ][ $field_name ] = '';

		return http_build_query( $rule_rewrite );
	}
}
