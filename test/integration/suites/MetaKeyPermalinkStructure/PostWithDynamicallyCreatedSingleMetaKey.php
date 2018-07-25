<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink\Tests\Integration\MetaKeyPermalinkStructure;

use BaseTestCase;
use WP_Post;

/**
 * Class PostWithDynamicallyCreatedSingleMetaKey
 */
class PostWithDynamicallyCreatedSingleMetaKey extends BaseTestCase {

	/**
	 * Filters of retrieved single metadata of a post to link rewrite.
	 *
	 * @since 1.4.0
	 *
	 * @param array|null $values The metadata values returned from get_post_meta.
	 * @param string     $field_name Name of metadata field.
	 * @param array      $field_attr The metadata field rewrite permalink attributes.
	 * @param WP_Post    $post The post object.
	 *
	 * @return array original values
	 */
	function get_post_metadata_single( $values, $field_name, $field_attr, $post ) {
		if ($field_name === 'some_meta_key') {
			return 'default-value';
		}

		return null;
	}

	/**
	 * Test step that hook wpcfp_get_post_metadata has been registered.
	 */
	private function given_hook_registered() {
		add_filter( 'wpcfp_get_post_metadata_single', array( $this, 'get_post_metadata_single' ), 1, 4 );
	}

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_while_missing_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		$this->given_hook_registered();

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/default-value/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_when_dynamic_generated_meta_value_without_hook() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/default-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id )
			->and_also()
			->then_is_404();
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_when_dynamic_generated_meta_value_with_hook() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		$this->given_hook_registered();

		// when.
		$this->go_to( '/default-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );
	}
}
