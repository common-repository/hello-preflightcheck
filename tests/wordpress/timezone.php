<?php

/**
 * tests if the time zone is another than the default UTC+0
 */

$timezone = get_option('timezone_string');

if ($timezone == 'UTC') {
	$check->warning("The timezone is <strong>$timezone</strong>, which is the timezones default value. Is this intended?");
} else {
	$check->success("The timezone is <strong>$timezone</strong>");
}
