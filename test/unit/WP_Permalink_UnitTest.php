<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

use CustomFieldsPermalink\Field_Attributes;
use CustomFieldsPermalink\WP_Permalink;
use CustomFieldsPermalink\WP_Post_Meta;

/**
 * Class WP_Permalink_UnitTest
 */
class WP_Permalink_UnitTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test case.
	 */
	public function test_constructs_object() {
		// given.
		$post_meta        = new WP_Post_Meta();
		$field_attributes = new Field_Attributes();

		// when.
		$permalink = new WP_Permalink( $post_meta, $field_attributes );

		// then no exception.
		$this->assertTrue( null !== $permalink );
	}
}
