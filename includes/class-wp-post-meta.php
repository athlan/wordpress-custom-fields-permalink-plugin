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
	 * @param WP_Post $post            The post.
	 * @param string  $meta_key        Name of metadata field.
	 * @param array   $meta_key_attrs  The metadata field rewrite permalink attributes.
	 *
	 * @return array
	 */
	public function get_post_meta_single( $post, $meta_key, array $meta_key_attrs ) {
		$post_meta = $this->get_post_meta( $post );

		if ( array_key_exists( $meta_key, $post_meta ) ) {
			$post_meta_value = $post_meta[ $meta_key ];
		} else {
			$post_meta_value = null;
		}

		/**
		 * Filters of retrieved single metadata of a post to link rewrite.
		 *
		 * @since 1.4.0
		 *
		 * @param mixed|null $post_meta_value  The metadata values returned from get_post_meta.
		 * @param string     $meta_key         Name of metadata field.
		 * @param array      $meta_key_attrs   The metadata field rewrite permalink attributes.
		 * @param WP_Post    $post             The post object.
		 */
		$filtered_post_meta_value = apply_filters( 'wpcfp_get_post_metadata_single', $post_meta_value, $meta_key, $meta_key_attrs, $post );
		if ( null === $filtered_post_meta_value ) {
			return null;
		}

		// Do some fixes after user generated values.
		// If it's single value, wrap this in array, as WordPress internally does.
		// @see get_post_meta() with $single = false.
		if ( ! is_array( $filtered_post_meta_value ) ) {
			$filtered_post_meta_value = array( $filtered_post_meta_value );
		}

		return $filtered_post_meta_value;
	}
}
