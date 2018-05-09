<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class CustomPostTypeWithMetaKey
 */
class CustomPostTypeWithMetaKey extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_using_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$this->custom_post_type_steps->given_custom_post_type( 'custom_post_type' )
			->with_rewrite_slug( 'custom/%field_some_meta_key%' )
			->end();

		$post_params     = array(
			'post_title' => 'Some post title',
			'post_type'  => 'custom_post_type',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/custom/some-meta-value/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_using_meta_key_permalink_structure() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$this->custom_post_type_steps->given_custom_post_type( 'custom_post_type' )
			->with_rewrite_slug( 'custom/%field_some_meta_key%' )
			->end();

		$post_params     = array(
			'post_title' => 'Some post title',
			'post_type'  => 'custom_post_type',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/custom/some-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );
	}

	/**
	 * Test case.
	 */
	function test_not_go_to_the_post_when_invalid_value_of_meta_key_part_in_url() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$this->custom_post_type_steps->given_custom_post_type( 'custom_post_type' )
			->with_rewrite_slug( '%field_some_meta_key%' )
			->end();

		$post_params     = array(
			'post_title' => 'Some post title',
			'post_type'  => 'custom_post_type',
			'meta_input' => array(
				'some_meta_key' => 'Some meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/custom/some-different-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_not_displayed_post( $created_post_id );
	}
}
