<?php

$experimentals = array('chain_rewrite');

tests_add_filter( 'wpcfp_experiments', function () use ($experimentals) {
	return $experimentals;
});
