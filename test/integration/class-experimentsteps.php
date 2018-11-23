<?php
/**
 * Tests util file.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class ExperimentSteps manages enabled experiments.
 */
class ExperimentSteps {

	/**
	 * Enabled experiments.
	 *
	 * @var array
	 */
	public $enabled_experiments = array();

	/**
	 * PermalinkSteps constructor.
	 */
	public function __construct() {
		$that = $this;
		add_filter(
			'wpcfp_experiments', function() use ( &$that ) {
				return $that->enabled_experiments;
			}
		);
	}

	/**
	 * Enabled an experiment by name.
	 *
	 * @param string $name Experiment name.
	 */
	public function given_experiment_enabled( $name ) {
		$this->enabled_experiments[] = $name;
	}
}
