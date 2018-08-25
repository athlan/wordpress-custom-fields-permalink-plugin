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
		$field_attributes  = new Field_Attributes();
		$attributes_string = 'attr_one';

		// when.
		$attributes = $field_attributes->parse_attributes( $attributes_string );

		// then no exception.
		$this->assertArrayHasKey( 'attr_one', $attributes );
		$this->assertTrue( true === $attributes['attr_one'] );
	}

	/**
	 * Test case.
	 */
	public function test_parses_single_attribute_with_value() {
		// given.
		$field_attributes  = new Field_Attributes();
		$attributes_string = 'attr_one=value1';

		// when.
		$attributes = $field_attributes->parse_attributes( $attributes_string );

		// then no exception.
		$this->assertArrayHasKey( 'attr_one', $attributes );
		$this->assertTrue( 'value1' === $attributes['attr_one'] );
	}

	/**
	 * Test case.
	 */
	public function test_parses_multiple_attributes() {
		// given.
		$field_attributes  = new Field_Attributes();
		$attributes_string = 'attr_one attr_two=value2 attr_three';

		// when.
		$attributes = $field_attributes->parse_attributes( $attributes_string );

		// then no exception.
		$this->assertArrayHasKey( 'attr_one', $attributes );
		$this->assertArrayHasKey( 'attr_two', $attributes );
		$this->assertArrayHasKey( 'attr_three', $attributes );
		$this->assertTrue( true === $attributes['attr_one'] );
		$this->assertTrue( 'value2' === $attributes['attr_two'] );
		$this->assertTrue( true === $attributes['attr_three'] );
	}

	/**
	 * Test case.
	 */
	public function test_parses_multiple_attributes_one_in_quote() {
		// given.
		$field_attributes  = new Field_Attributes();
		$attributes_string = 'attr_one attr_two=\'some value2\' attr_three';

		// when.
		$attributes = $field_attributes->parse_attributes( $attributes_string );

		// then no exception.
		$this->assertArrayHasKey( 'attr_one', $attributes );
		$this->assertArrayHasKey( 'attr_two', $attributes );
		$this->assertArrayHasKey( 'attr_three', $attributes );
		$this->assertTrue( true === $attributes['attr_one'] );
		$this->assertTrue( 'some value2' === $attributes['attr_two'] );
		$this->assertTrue( true === $attributes['attr_three'] );
	}
}
