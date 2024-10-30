<?php

/**
 * tests if the debug mode of WordPress is activated
 */

if (defined('WP_DEBUG') && WP_DEBUG) {
	$check->error("WP_DEBUG is set to true. Therefore WordPress runs in debug mode");
} else {
	$check->success("Debug mode is off");
}
