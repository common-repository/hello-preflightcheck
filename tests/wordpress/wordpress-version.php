<?php

/**
 * tests if the installed WordPress version is the most current
 */

$is     = get_bloginfo('version');
$should = get_preferred_from_update_core()->current;

if ($is == $should) {
	$check->success("You are using the current version, which is $should");
} else {
	$check->error("You are using version <b>$is</b>, which is not the current version <b>$should</b>.");
}
