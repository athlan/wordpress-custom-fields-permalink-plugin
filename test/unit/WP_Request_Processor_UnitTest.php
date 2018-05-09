<?php
/**
 * Tests case.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

use CustomFieldsPermalink\WP_Post_Meta;
use CustomFieldsPermalink\WP_Request_Processor;

/**
 * Class WP_Request_Processor_UnitTest
 */
class WP_Request_Processor_UnitTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test case.
	 */
	public function test_constructs_object() {
		// given.
		$post_meta = new WP_Post_Meta();

		// when.
		$request_processor = new WP_Request_Processor( $post_meta );

		// then no exception.
		$this->assertTrue( null !== $request_processor );
	}
}
