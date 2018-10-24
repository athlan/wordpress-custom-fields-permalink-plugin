<?php
/**
 * Tests util file.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class AuthSteps contains utility methods for authentication.
 */
class AuthSteps {

	/**
	 * AuthSteps constructor.
	 */
	public function __construct() {
	}

	/**
	 * Logged as given user and password.
	 *
	 * @param string $username User name.
	 * @param string $password User password.
	 * @throws Exception When authentication fails.
	 */
	public function given_logged_as( $username, $password ) {
		$result = wp_signon(
			array(
				'user_login'    => $username,
				'user_password' => $password,
			)
		);

		if ( ! ( $result instanceof WP_User ) ) {
			throw new Exception( "Couldn't login user" );
		}

		wp_set_current_user( $result->ID, $result->user_login );
	}

	/**
	 * Logged as admin.
	 */
	public function given_logged_as_admin() {
		$this->given_logged_as( 'admin', 'password' );
	}
}
