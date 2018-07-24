<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

use CustomFieldsPermalink\Field_Attributes;

/**
 * Class Field_Attributes_UnitTest
 */
class Field_Attributes_UnitTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test case.
	 */
	public function test_parses_single_attribute_without_value() {
		// given.
		$field_attributes = new Field_Attributes();
		$attributes_string = "attr_one";

		// when.
		$attributes = $field_attributes->parse_attributes($attributes_string);

		// then no exception.
		$this->assertArrayHasKey( "attr_one", $attributes );
		$this->assertTrue( $attributes["attr_one"] === true );
	}

	/**
	 * Test case.
	 */
	public function test_parses_single_attribute_with_value() {
		// given.
		$field_attributes = new Field_Attributes();
		$attributes_string = "attr_one=value1";

		// when.
		$attributes = $field_attributes->parse_attributes($attributes_string);

		// then no exception.
		$this->assertArrayHasKey( "attr_one", $attributes );
		$this->assertTrue( $attributes["attr_one"] === "value1" );
	}

	/**
	 * Test case.
	 */
	public function test_parses_multiple_attributes() {
		// given.
		$field_attributes = new Field_Attributes();
		$attributes_string = "attr_one attr_two=value2 attr_three";

		// when.
		$attributes = $field_attributes->parse_attributes($attributes_string);

		// then no exception.
		$this->assertArrayHasKey( "attr_one", $attributes );
		$this->assertArrayHasKey( "attr_two", $attributes );
		$this->assertArrayHasKey( "attr_three", $attributes );
		$this->assertTrue( $attributes["attr_one"] === true );
		$this->assertTrue( $attributes["attr_two"] === "value2" );
		$this->assertTrue( $attributes["attr_three"] === true );
	}
}
