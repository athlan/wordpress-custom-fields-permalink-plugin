<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink\Tests\Integration\MetaKeyPermalinkStructure;

use BaseTestCase;

/**
 * Class PrivatePostWithMetaKey
 */
class PrivatePostWithMetaKey extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_private_post() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = array(
			'post_title'  => 'Some post title',
			'post_status' => 'private',
			'meta_input'  => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-meta-value/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_private_post_using_meta_key_permalink_structure() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = array(
			'post_title'  => 'Some post title',
			'post_status' => 'private',
			'meta_input'  => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id )
			->and_also()
			->then_is_404();
	}
}
