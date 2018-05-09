<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class PostWithMissingMetaKey
 */
class PostWithMissingMetaKey extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_while_missing_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_404_when_missing_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/inexisting-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id )
			->and_also()
			->then_is_404();
	}

	/**
	 * Test case.
	 */
	function test_not_go_to_the_post_when_missing_meta_key_part_in_url() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key' => 'Some meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-post-title/' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id );
	}
}
