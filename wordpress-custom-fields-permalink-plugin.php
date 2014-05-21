<?php
/*
Plugin Name: Custom Fields Permalink 2
Plugin URI: http://athlan.pl/wordpress-custom-fields-permalink-plugin
Description: Plugin allows to use post's custom fields values in permalink structure by adding %field_fieldname%, for posts, pages and custom post types.
Author: Piotr Pelczar
Version: 2.0
Author URI: http://athlan.pl/
*/

class CustomFieldsPermalink {

	const PARAM_CUSTOMFIELD_KEY = 'custom_field_key';
	const PARAM_CUSTOMFIELD_VALUE = 'custom_field_value';
	
	public static function linkPost($permalink, $post, $leavename) {
		return self::linkRewriteFields($permalink, $post);
	}
	
	public static function linkPostType($permalink, $post, $leavename, $sample) {
		return self::linkRewriteFields($permalink, $post);
	}
	
	protected static function linkRewriteFields($permalink, $post) {
		return preg_replace('#(%field_(.*?)%)#e', 'CustomFieldsPermalink::linkRewriteFieldsExtract($post, "\\2")', $permalink);
	}
	
	public static function linkRewriteFieldsExtract($post, $fieldName) {
		$postMeta = get_post_meta($post->ID);
		
		if(!isset($postMeta[$fieldName]))
			return '';
		
		return implode('', $postMeta[$fieldName]);
	}
	
	public static function registerExtraQueryVars($value) {
		array_push($value, self::PARAM_CUSTOMFIELD_KEY, self::PARAM_CUSTOMFIELD_VALUE);
		return $value;
	}
	
	public static function processRequest($value) {
		// additional parameters added to Wordpress
		// Main Loop query
		$value['meta_key'] = $value[self::PARAM_CUSTOMFIELD_KEY];
		$value['meta_value'] = $value[self::PARAM_CUSTOMFIELD_VALUE];
		
		// remove temporary injected parameters
		unset($value[self::PARAM_CUSTOMFIELD_KEY], $value[self::PARAM_CUSTOMFIELD_VALUE]);
		
		return $value;
	}
	
	public static function rewriteRulesArrayFilter($rules) {
		$keys = array_keys($rules);
		$tmp = $rules;
		$rules = array();
		
		for($i = 0, $j = sizeof($keys); $i < $j; ++$i) {
			$key = $keys[$i];
			
			if (preg_match('/%field_([^%]*?)%/', $key)) {
				$keyNew = preg_replace(
					'/%field_([^%]*?)%/',
					'([^/]+)',
					// you can simply add next group to the url, because WordPress
					// detect them automatically and add next $matches indiceis
					$key
				);
				$rules[$keyNew] = preg_replace(
					'/%field_([^%]*?)%/',
					sprintf('%s=$1&%s=', self::PARAM_CUSTOMFIELD_KEY, self::PARAM_CUSTOMFIELD_VALUE),
					// here on the end will be pasted $matches[$i] from $keyNew, so we can
					// grab it it the future in self::PARAM_CUSTOMFIELD_VALUE parameter
					$tmp[$key]
				);
			}
			else {
				$rules[$key] = $tmp[$key];
			}
		}
		
		return $rules;
	}
}

add_filter('pre_post_link', array('CustomFieldsPermalink', 'linkPost'), 100, 3);
add_filter('post_type_link', array('CustomFieldsPermalink', 'linkPostType'), 100, 4);
add_filter('rewrite_rules_array', array('CustomFieldsPermalink', 'rewriteRulesArrayFilter'));
add_filter('query_vars', array('CustomFieldsPermalink', 'registerExtraQueryVars'), 10, 1);
add_filter('request', array('CustomFieldsPermalink', 'processRequest'), 10, 1);
