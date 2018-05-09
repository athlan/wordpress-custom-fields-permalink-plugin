<?php
/**
 * Tests util file.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class NavigationAsserter contains utility methods to assert conditions related to navigation.
 */
class NavigationAsserter {

	/**
	 * Parent unit test case.
	 *
	 * @var WP_UnitTestCase
	 */
	private $unit_test_case;

	/**
	 * NavigationAsserter constructor.
	 *
	 * @param WP_UnitTestCase $unit_test_case Parent unit test case.
	 */
	public function __construct( WP_UnitTestCase $unit_test_case ) {
		$this->unit_test_case = $unit_test_case;
	}

	/**
	 * Checks if post is displayed
	 *
	 * @param WP_Post|int $post The post or id.
	 *
	 * @return NavigationAsserter Fluent interface.
	 */
	public function then_displayed_post( $post ) {
		$this->unit_test_case->assertTrue( is_single( $post ) );
		return $this;
	}

	/**
	 * Checks if post is not displayed
	 *
	 * @param WP_Post|int $post The post or id.
	 *
	 * @return NavigationAsserter Fluent interface.
	 */
	public function then_not_displayed_post( $post ) {
		$this->unit_test_case->assertFalse( is_single( $post ) );
		return $this;
	}

	/**
	 * Checks if page is displayed
	 *
	 * @param WP_Post|int $post The post or id.
	 *
	 * @return NavigationAsserter Fluent interface.
	 */
	public function then_displayed_page( $post ) {
		$this->unit_test_case->assertTrue( is_page( $post ) );
		return $this;
	}

	/**
	 * Checks if page is not displayed
	 *
	 * @param WP_Post|int $post The post or id.
	 *
	 * @return NavigationAsserter Fluent interface.
	 */
	public function then_not_displayed_page( $post ) {
		$this->unit_test_case->assertFalse( is_page( $post ) );
		return $this;
	}

	/**
	 * Checks if 404 page is reached
	 *
	 * @return NavigationAsserter Fluent interface.
	 */
	public function then_is_404() {
		$this->unit_test_case->assertTrue( is_404() );
		return $this;
	}

	/**
	 * Fluent interface.
	 *
	 * @return NavigationAsserter Fluent interface.
	 */
	public function and_also() {
		return $this;
	}

}
