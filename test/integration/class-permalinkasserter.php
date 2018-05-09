<?php
/**
 * Tests util file.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class PermalinkAsserter contains utility methods to assert conditions related to permalinks.
 */
class PermalinkAsserter {

	/**
	 * Parent unit test case.
	 *
	 * @var WP_UnitTestCase
	 */
	private $unit_test_case;

	/**
	 * PermalinkAsserter constructor.
	 *
	 * @param WP_UnitTestCase $unit_test_case Parent unit test case.
	 */
	public function __construct( WP_UnitTestCase $unit_test_case ) {
		$this->unit_test_case = $unit_test_case;
	}

	/**
	 * Checks if post has an expected permalink.
	 *
	 * @param WP_Post $post The post.
	 * @param string  $permalink Expected permalink.
	 *
	 * @return PermalinkAsserter Fluent interface.
	 */
	public function has_permalink( $post, $permalink ) {
		$actual = wp_make_link_relative( get_the_permalink( $post ) );

		$this->unit_test_case->assertEquals( $permalink, $actual );

		return $this;
	}

	/**
	 * Fluent interface.
	 *
	 * @return PermalinkAsserter Fluent interface.
	 */
	public function and_also() {
		return $this;
	}
}
