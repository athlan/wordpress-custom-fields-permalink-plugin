<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink\Tests\Integration\MetaKeyPermalinkStructure;

use BaseTestCase;

/**
 * Class PostWithDynamicallyCreatedMetaKey
 */
class PostWithDynamicallyCreatedMetaKey extends BaseTestCase {

	/**
	 * Sample custom metadata filter that adds some metadata to the array.
	 *
	 * @param array   $post_meta  The metadata returned from get_post_meta.
	 * @param WP_Post $post       The post object.
	 *
	 * @return array metadata
	 */
	function generate_dynamic_metadata( $post_meta = null, $post = null ) {
		if ( ! array_key_exists( 'some_meta_key', $post_meta ) ) {
			$post_meta['some_meta_key'] = 'Default value';
		}

		return $post_meta;
	}

	/**
	 * Test step that hook wpcfp_get_post_metadata has been registered.
	 */
	private function given_hook_registered() {
		add_filter( 'wpcfp_get_post_metadata', array( $this, 'generate_dynamic_metadata' ), 1, 2 );
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
