<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class PostWithMissingMetaKey
 */
class PostWithMissingMetaKey extends WP_UnitTestCase {

	/**
	 * The PermalinkSteps.
	 *
	 * @var PermalinkSteps
	 */
	private $permalink_steps;

	/**
	 * The PermalinkAsserter.
	 *
	 * @var PermalinkAsserter
	 */
	private $permalink_asserter;

	/**
	 * Set up test.
	 */
	public function setUp() {
		parent::setUp();

		$this->permalink_steps    = new PermalinkSteps( $this );
		$this->permalink_asserter = new PermalinkAsserter( $this );
	}

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_while_missing_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = [
			'post_title' => 'Some post title',
			'meta_input' => [
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			],
		];
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_when_missing_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = [
			'post_title' => 'Some post title',
			'meta_input' => [
				// There is missing meta key here
				// 'some_meta_key' => 'Some meta value', .
			],
		];
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/inexisting-meta-value/some-post-title/' );

		// then.
		$this->assertTrue( is_404() );
	}
}
