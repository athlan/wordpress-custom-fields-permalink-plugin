<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class PageWithMetaKey
 *
 * Those test cases proves the default WordPress functionality which
 * displays pages without using permalink structure.
 */
class PageWithMetaKey extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_page_not_using_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$page_params     = array(
			'post_type'  => 'page',
			'post_title' => 'Some page title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_page_id = $this->factory()->post->create( $page_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_page_id, '/some-page-title/' );
	}

	/**
	 * Test case.
	 */
	function IGNORE_test_go_to_page_not_using_meta_key_permalink_structure() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$post_params     = array(
			'post_type'  => 'page',
			'post_title' => 'Some page title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_page_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-page-title/' );

		// then.
		$this->navigation_asserter->then_displayed_page( $created_page_id );
	}
}
