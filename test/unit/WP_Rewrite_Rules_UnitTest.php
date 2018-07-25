<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

use CustomFieldsPermalink\Field_Attributes;
use CustomFieldsPermalink\WP_Rewrite_Rules;

/**
 * Class WP_Rewrite_Rules_UnitTest
 */
class WP_Rewrite_Rules_UnitTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test case.
	 */
	public function test_constructs_object() {
		// given.
		$field_attributes = new Field_Attributes();

		// when.
		$rewrite_rules = new WP_Rewrite_Rules( $field_attributes );

		// then no exception.
		$this->assertTrue( null !== $rewrite_rules );
	}
}
