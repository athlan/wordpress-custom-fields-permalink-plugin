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
	 * WP_Permalink constructor.
	 *
	 * @param WP_Post_Meta $post_meta Post meta provider.
	 */
	public function __construct( WP_Post_Meta $post_meta ) {
		$this->post_meta = $post_meta;
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
			return $that->link_rewrite_fields_extract( $post, $matches[2] );
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
	public function link_rewrite_fields_extract( $post, $field_name ) {
		$post_meta = $this->post_meta->get_post_meta( $post );
		if ( ! isset( $post_meta[ $field_name ] ) ) {
			return '';
		}
		$value = $post_meta[ $field_name ][0];
		$value = sanitize_title( $value );
		return $value;
	}
}
