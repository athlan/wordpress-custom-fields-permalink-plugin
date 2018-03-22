<?php

class PermalinkAsserter
{
    private $unitTestCase;

    public function __construct(WP_UnitTestCase $unitTestCase) {
        $this->unitTestCase = $unitTestCase;
    }

    public function hasPermalink($post, $permalink) {
        $actual = wp_make_link_relative(get_the_permalink($post));

        $this->unitTestCase->assertEquals($permalink, $actual);
    }
}