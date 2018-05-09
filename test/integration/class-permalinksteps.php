<?php
/**
 * Tests util file.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class PermalinkSteps contains utility methods to arrange conditions related to permalinks.
 */
class PermalinkSteps {

	/**
	 * PermalinkSteps constructor.
	 */
	public function __construct() {
	}

	/**
	 * Sets the given permalink structure.
	 *
	 * @param string $structure The permalink structure.
	 */
	public function given_permalink_structure( $structure ) {
		global $wp_rewrite;

		$wp_rewrite->init();
		$wp_rewrite->set_permalink_structure( $structure );
		$wp_rewrite->flush_rules();
	}

	/**
	 * Sets the "/%postname%/" permalink structure.
	 */
	public function given_postname_permalink_structure() {
		$this->given_permalink_structure( '/%postname%/' );
	}
}
