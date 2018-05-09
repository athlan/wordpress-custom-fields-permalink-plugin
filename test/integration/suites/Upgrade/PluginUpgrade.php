<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

use CustomFieldsPermalink\Plugin_Updater;

/**
 * Class PluginUpgrade
 */
class PluginUpgrade extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_flushes_rules_after_plugin_upgrade() {
		// given.
		global $wp_rewrite;

		$plugin_updater = new Plugin_Updater();
		$this->given_stored_plugin_version( null );
		$this->given_rewrite_rules_are_corrupted();

		// when.
		$plugin_updater->on_init_hook();

		// then.
		$rules = $wp_rewrite->wp_rewrite_rules();

		$this->assertNotEquals( 'CORRUPTED', $rules );
		$this->assertPluginVersionUpdated();
	}

	/**
	 * Update plugin version.
	 *
	 * @param string $version The plugin version.
	 */
	private function given_stored_plugin_version( $version ) {
		update_option( '_wordpress_custom_fields_permalink_plugin_version', $version );
	}

	/**
	 * Makes rewrite rules corrupted.
	 */
	private function given_rewrite_rules_are_corrupted() {
		global $wp_rewrite;

		update_option( 'rewrite_rules', 'CORRUPTED' );
		$rules = $wp_rewrite->wp_rewrite_rules();

		// Sanity check.
		$this->assertEquals( 'CORRUPTED', $rules );
	}

	/**
	 * Assert if plugin version is up to date.
	 */
	private function assertPluginVersionUpdated() {
		$this->assertEquals( WORDPRESS_CUSTOM_FIELDS_PERMALINK_PLUGIN_VERSION, get_option( '_wordpress_custom_fields_permalink_plugin_version' ) );
	}
}
