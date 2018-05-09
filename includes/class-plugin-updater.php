<?php
/**
 * Class Updater
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

/**
 * Class Plugin_Updater handles the upgrade process.
 */
class Plugin_Updater {

	/**
	 * This hook is called once any activated plugins have been loaded.
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
	 */
	public function on_init_hook() {
		$version_option_name = '_wordpress_custom_fields_permalink_plugin_version';
		$version_from        = get_option( $version_option_name, null );
		$version_to          = WORDPRESS_CUSTOM_FIELDS_PERMALINK_PLUGIN_VERSION;
		if ( $version_from != $version_to ) {
			$this->update_plugin( $version_from, $version_to );
			update_option( $version_option_name, $version_to, true );
		}
	}

	/**
	 * Upgrades the plugin.
	 *
	 * @param string $version_from Currently running version.
	 * @param string $version_to   Version upgrade to.
	 */
	private function update_plugin( $version_from, $version_to ) {
		flush_rewrite_rules();
	}
}
