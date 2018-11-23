<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink\Tests\Integration\MetaKeyPermalinkStructure;

use BaseTestCase;

/**
 * Class NestedPageWithMetaKey
 *
 * Those test cases proves the default WordPress functionality which
 * displays pages without using permalink structure.
 */
class NestedPageWithMetaKey extends BaseTestCase {

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_nested_page_not_using_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$parent_page_params     = array(
			'post_type'  => 'page',
			'post_title' => 'Parent page title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_parent_page_id = $this->factory()->post->create( $parent_page_params );

		$page_params           = array(
			'post_type'   => 'page',
			'post_parent' => $created_parent_page_id,
			'post_title'  => 'Child page title',
			'meta_input'  => array(
				'some_meta_key'       => 'Child Some meta value',
				'some_other_meta_key' => 'Child Some other meta value',
			),
		);
		$created_child_page_id = $this->factory()->post->create( $page_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_child_page_id, '/parent-page-title/child-page-title/' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_nested_page_not_using_meta_key_permalink_structure() {
		// given.
		$this->experiment_steps->given_experiment_enabled( 'chain_rewrite' );
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key%/%postname%/' );

		$parent_page_params     = array(
			'post_type'  => 'page',
			'post_title' => 'Parent page title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_parent_page_id = $this->factory()->post->create( $parent_page_params );

		$page_params           = array(
			'post_type'   => 'page',
			'post_parent' => $created_parent_page_id,
			'post_title'  => 'Child page title',
			'meta_input'  => array(
				'some_meta_key'       => 'Child Some meta value',
				'some_other_meta_key' => 'Child Some other meta value',
			),
		);
		$created_child_page_id = $this->factory()->post->create( $page_params );

		// when.
		$this->go_to( '/parent-page-title/child-page-title/' );

		// then.
		$this->navigation_asserter->then_displayed_page( $created_child_page_id );
	}
}
