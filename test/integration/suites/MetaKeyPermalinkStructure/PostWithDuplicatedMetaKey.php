<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink\Tests\Integration\MetaKeyPermalinkStructure;

use BaseTestCase;

/**
 * Class PostWithDuplicatedMetaKey
 */
class PostWithDuplicatedMetaKey extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_while_duplicated_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$some_meta_key   = 'some_meta_key';
		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				$some_meta_key => 'Some meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );
		add_post_meta( $created_post_id, $some_meta_key, 'Some duplicated meta value' );

		// when.
		$created_post_meta_values = get_post_meta( $created_post_id );

		// then.
		$this->assertCount( 2, $created_post_meta_values[ $some_meta_key ] );
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-meta-value/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_when_duplicated_meta_key_and_use_first_one() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$some_meta_key   = 'some_meta_key';
		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				$some_meta_key => 'Some meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );
		add_post_meta( $created_post_id, $some_meta_key, 'Some duplicated meta value' );

		// when.
		$this->go_to( '/some-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_when_duplicated_meta_key_and_use_duplicate_one() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$some_meta_key   = 'some_meta_key';
		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				$some_meta_key => 'Some meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );
		add_post_meta( $created_post_id, $some_meta_key, 'Some duplicated meta value' );

		// when.
		$this->go_to( '/some-duplicated-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );
	}
}
