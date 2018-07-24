<?php
/**
 * Class RequestProcessor
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

use WP_Post;

/**
 * Class WP_Permalink generates WordPress permalinks.
 */
class WP_Permalink {

	/**
	 * Post meta provider.
	 *
	 * @var WP_Post_Meta
	 */
	private $post_meta;

	/**
	 * Field attributes parser.
	 *
	 * @var Field_Attributes
	 */
	private $field_attributes;

	/**
	 * WP_Permalink constructor.
	 *
	 * @param WP_Post_Meta     $post_meta Post meta provider.
	 * @param Field_Attributes $field_attributes Field attributes parser.
	 */
	public function __construct( WP_Post_Meta $post_meta, Field_Attributes $field_attributes ) {
		$this->post_meta        = $post_meta;
		$this->field_attributes = $field_attributes;
	}

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
	public function link_post( $permalink, $post, $leavename ) {
		return $this->link_rewrite_fields( $permalink, $post );
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
	public function link_post_type( $permalink, $post, $leavename, $sample ) {
		return $this->link_rewrite_fields( $permalink, $post );
	}

	/**
	 * Rewrites permalink replacing custom fields.
	 *
	 * @param string  $permalink The permalink.
	 * @param WP_Post $post      The post.
	 *
	 * @return string
	 */
	private function link_rewrite_fields( $permalink, $post ) {
		$that             = $this;
		$replace_callback = function ( $matches ) use ( &$post, &$that ) {
			$field_name = $matches[ WP_Rewrite_Rules::FIELD_REGEXP_NAME_GROUP ];

			if ( isset( $matches[ WP_Rewrite_Rules::FIELD_REGEXP_ATTRIBUTES_GROUP ] ) ) {
				$field_attr = $that->field_attributes->parse_attributes( $matches[ WP_Rewrite_Rules::FIELD_REGEXP_ATTRIBUTES_GROUP ] );
			} else {
				$field_attr = array();
			}

			return $that->link_rewrite_fields_extract( $post, $field_name, $field_attr );
		};

		return preg_replace_callback( '#' . WP_Rewrite_Rules::FIELD_REGEXP . '#', $replace_callback, $permalink );
	}

	/**
	 * Extract the metadata value from the post.
	 *
	 * @param WP_Post $post       The post.
	 * @param string  $field_name The metadata key to extract.
	 * @param array   $field_attr The metadata field rewrite permalink attributes.
	 *
	 * @return string
	 */
	public function link_rewrite_fields_extract( $post, $field_name, array $field_attr ) {
		$post_meta_value = $this->post_meta->get_post_meta_single( $post, $field_name, $field_attr );
		if ( ! isset( $post_meta_value ) ) {
			return '';
		}
		$value = $post_meta_value[0];
		$value = sanitize_title( $value );

		return $value;
	}
}
