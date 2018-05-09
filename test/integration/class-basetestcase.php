<?php
/**
 * Base Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class BaseTestCase
 */
class BaseTestCase extends WP_UnitTestCase {

	/**
	 * The PermalinkSteps.
	 *
	 * @var PermalinkSteps
	 */
	protected $permalink_steps;

	/**
	 * The CustomPostTypeSteps.
	 *
	 * @var CustomPostTypeSteps
	 */
	protected $custom_post_type_steps;

	/**
	 * The PermalinkAsserter.
	 *
	 * @var PermalinkAsserter
	 */
	protected $permalink_asserter;

	/**
	 * The NavigationAsserter.
	 *
	 * @var NavigationAsserter
	 */
	protected $navigation_asserter;

	/**
	 * Set up test.
	 */
	public function setUp() {
		parent::setUp();

		$this->permalink_steps        = new PermalinkSteps( $this );
		$this->custom_post_type_steps = new CustomPostTypeSteps( $this );
		$this->permalink_asserter     = new PermalinkAsserter( $this );
		$this->navigation_asserter    = new NavigationAsserter( $this );
	}
}
