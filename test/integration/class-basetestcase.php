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
	 * The AuthSteps.
	 *
	 * @var AuthSteps
	 */
	protected $auth_steps;

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
	 * The ExperimentSteps.
	 *
	 * @var ExperimentSteps
	 */
	protected $experiment_steps;

	/**
	 * Set up test.
	 */
	public function setUp() {
		parent::setUp();

		$this->permalink_steps        = new PermalinkSteps( $this );
		$this->custom_post_type_steps = new CustomPostTypeSteps( $this );
		$this->auth_steps             = new AuthSteps( $this );
		$this->permalink_asserter     = new PermalinkAsserter( $this );
		$this->navigation_asserter    = new NavigationAsserter( $this );
		$this->experiment_steps       = new ExperimentSteps( $this );
	}
}
