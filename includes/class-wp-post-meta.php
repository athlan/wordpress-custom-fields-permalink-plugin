<?php
/**
 * Class RequestProcessor
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

use WP_Post;

/**
 * Class WP_Post_Meta gets post metadata.
 */
class WP_Post_Meta {

	/**
	 * Get post meta applying <code>wpcfp_get_post_metadata</code> filter.
	 *
	 * @param WP_Post $post The post.
	 *
	 * @return array
	 */
	public function get_post_meta( $post ) {
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

	/**
	 * Get single post meta applying <code>wpcfp_get_post_metadata_single</code> filter.
	 *
	 * @param WP_Post $post        The post.
	 * @param string  $field_name  Name of metadata field.
	 * @param array   $field_attr  The metadata field rewrite permalink attributes.
	 *
	 * @return array
	 */
	public function get_post_meta_single( $post, $field_name, array $field_attr ) {
		$post_meta = $this->get_post_meta( $post );

		if ( array_key_exists( $field_name, $post_meta ) ) {
			$values = $post_meta[ $field_name ];
		} else {
			$values = null;
		}

		/**
		 * Filters of retrieved single metadata of a post to link rewrite.
		 *
		 * @since 1.4.0
		 *
		 * @param array|null  $values      The metadata values returned from get_post_meta.
		 * @param string      $field_name  Name of metadata field.
		 * @param array       $field_attr  The metadata field rewrite permalink attributes.
		 * @param WP_Post     $post        The post object.
		 */
		$filtered_value = apply_filters( 'wpcfp_get_post_metadata_single', $values, $field_name, $field_attr, $post );
		if ( null === $filtered_value ) {
			return null;
		}
		
		// Do some fixes after user generated values.
		// If it's single value, wrap this in array, as WordPress internally does.
		// @see get_post_meta() with $single = false.
		if ( ! is_array( $filtered_value ) ) {
			$filtered_value = array( $filtered_value );
		}

		return $filtered_value;
	}
}
