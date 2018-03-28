<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class BasicPostNamePermalinkStructure
 */
class BasicPostNamePermalinkStructure extends WP_UnitTestCase {

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
	function test_generates_permalink_to_post() {
		// given.
		$this->permalink_steps->given_postname_permalink_structure();

		$post_params     = [
			'post_title' => 'Some post title',
			'meta_input' => [
				'some_meta_key' => 'Some meta value',
			],
		];
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-post-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_when_simple_postname_permalink_structure_and_plugin_activated() {
		// given.
		$this->permalink_steps->given_postname_permalink_structure();

		$post_params     = [
			'post_title' => 'Some post title',
			'meta_input' => [
				'some_meta_key' => 'Some meta value',
			],
		];
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-post-title/' );

		// then.
		$this->assertFalse( is_404() );
		$this->assertEquals( $created_post_id, get_the_ID() );
	}
}
