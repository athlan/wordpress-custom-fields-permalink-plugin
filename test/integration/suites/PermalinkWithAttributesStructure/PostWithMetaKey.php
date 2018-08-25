<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink\Tests\Integration\PermalinkWithAttributesStructure;

use BaseTestCase;
use WP_Post;

/**
 * Class PostWithMetaKey
 */
class PostWithMetaKey extends BaseTestCase {

	/**
	 * The "wpcfp_get_post_metadata_single" hook calls.
	 *
	 * @var array
	 */
	private $hook_calls = array();

	/**
	 * Sample custom metadata filter that adds some metadata to the array.
	 *
	 * @param mixed|null $post_meta_value  The metadata values returned from get_post_meta.
	 * @param string     $meta_key         Name of metadata field.
	 * @param array      $meta_key_attrs   The metadata field rewrite permalink attributes.
	 * @param WP_Post    $post             The post object.
	 *
	 * @return mixed original values
	 */
	function get_post_metadata_single( $post_meta_value, $meta_key, $meta_key_attrs, $post ) {
		$this->hook_calls[ $meta_key ] = array(
			'post_meta_value' => $post_meta_value,
			'meta_key'        => $meta_key,
			'meta_key_attrs'  => $meta_key_attrs,
			'post'            => $post,
		);

		return $post_meta_value;
	}

	/**
	 * Test step that hook wpcfp_get_post_metadata has been registered.
	 */
	private function given_hook_registered() {
		$this->hook_calls = array();
		add_filter( 'wpcfp_get_post_metadata_single', array( $this, 'get_post_metadata_single' ), 1, 4 );
	}

	/**
	 * Test case.
	 */
	function test_generates_permalink_to_post_using_meta_key() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key(some_attribute)%/%postname%/' );
		$this->given_hook_registered();

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when & then.
		$this->permalink_asserter->has_permalink( $created_post_id, '/some-meta-value/some-post-title/' );

		$this->assertThatHookWasCalledWith(
			'some_meta_key', 'Some meta value',
			array( 'some_attribute' => true ),
			$created_post_id
		);
		$this->assertThatHookWasNotCalledForField( 'some_other_meta_key' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_using_meta_key_permalink_structure() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key(some_attribute)%/%postname%/' );
		$this->given_hook_registered();

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );

		$this->assertThatHookWasCalledWith(
			'some_meta_key', 'Some meta value',
			array( 'some_attribute' => true ),
			$created_post_id
		);
		$this->assertThatHookWasNotCalledForField( 'some_other_meta_key' );
	}

	/**
	 * Test case.
	 */
	function test_go_to_post_using_meta_key_permalink_structure_multiple_attributes() {
		// given.
		$this->permalink_steps->given_permalink_structure( '/%field_some_meta_key(some_attribute some_second_attribute=\'some value\')%/%postname%/' );
		$this->given_hook_registered();

		$post_params     = array(
			'post_title' => 'Some post title',
			'meta_input' => array(
				'some_meta_key'       => 'Some meta value',
				'some_other_meta_key' => 'Some other meta value',
			),
		);
		$created_post_id = $this->factory()->post->create( $post_params );

		// when.
		$this->go_to( '/some-meta-value/some-post-title/' );

		// then.
		$this->navigation_asserter->then_displayed_post( $created_post_id );

		$this->assertThatHookWasCalledWith(
			'some_meta_key', 'Some meta value',
			array(
				'some_attribute'        => true,
				'some_second_attribute' => 'some value',
			),
			$created_post_id
		);
		$this->assertThatHookWasNotCalledForField( 'some_other_meta_key' );
	}

	/**
	 * Asserter.
	 *
	 * @param string     $field_name Name of metadata field.
	 * @param mixed|null $value The metadata value returned from get_post_meta.
	 * @param array      $field_attr The metadata field rewrite permalink attributes.
	 * @param WP_Post    $post_id The post id.
	 */
	private function assertThatHookWasCalledWith( $field_name, $value, $field_attr, $post_id ) {
		$this->assertArrayHasKey( $field_name, $this->hook_calls );

		$hook_call = $this->hook_calls[ $field_name ];
		$this->assertTrue( $hook_call['post_meta_value'][0] === $value );
		$this->assertTrue( $hook_call['meta_key_attrs'] == $field_attr );
		$this->assertTrue( $hook_call['post']->ID === $post_id );
	}

	/**
	 * Asserter.
	 *
	 * @param string $field_name Name of metadata field.
	 */
	private function assertThatHookWasNotCalledForField( $field_name ) {
		$this->assertArrayNotHasKey( $field_name, $this->hook_calls );
	}
}
