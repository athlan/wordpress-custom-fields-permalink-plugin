<?php

class PermalinkSteps
{
    public function __construct() {
    }

    public function givenPermalinkStructure($structure) {
        global $wp_rewrite;

        $wp_rewrite->init();
        $wp_rewrite->set_permalink_structure($structure);
        $wp_rewrite->flush_rules();
    }

    public function givenPostnamePermalinkStructure() {
        $this->givenPermalinkStructure("/%postname%/");
    }
}
