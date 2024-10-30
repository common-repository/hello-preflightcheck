<?php

/**
 * tests if the current theme is maybe a default theme
 */

if (function_exists('wp_get_theme')) {
	$theme = wp_get_theme()->name; // introduced 3.4
} else {
	$theme = get_current_theme(); // deprecated >= 3.4
}

if (strtolower($theme) == 'twenty ten' || strtolower($theme) == 'twenty eleven') {
	$check->error("The current theme is <b>$theme</b>, the default theme of Wordpress 3");
} else {
	$check->success("The current theme <b>$theme</b> seems to be OK");
}
