<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class PostWithMetaKeyMultiple
 */
class PostWithMetaKeyMultiple extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_using_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%field_some_meta_key2%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_meta_key2'      => 'Some second meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-meta-value/some-second-meta-value/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_using_meta_key_permalink_structure() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%field_some_meta_key2%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_meta_key2'      => 'Some second meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		// Little hacky with "1" at the end, but wrong route is matched during the tests.
		$this->go_to( '/some-meta-value/some-second-meta-value/some-post-title/1' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );
	}

	/**
	 * Test case.
	 */
	function test_not_go_to_the_post_when_invalid_first_value_of_meta_key_part_in_url() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%field_some_meta_key2%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'  => 'Some meta value',
				'some_meta_key2' => 'Some second meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-different-meta-value/some-second-meta-value/some-post-title/1' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id );
	}

	/**
	 * Test case.
	 */
	function test_not_go_to_the_post_when_invalid_second_value_of_meta_key_part_in_url() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%field_some_meta_key2%/%postname%/' );

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'  => 'Some meta value',
				'some_meta_key2' => 'Some second meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-meta-value/some-different-meta-value/some-post-title/1' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id );
	}
}
