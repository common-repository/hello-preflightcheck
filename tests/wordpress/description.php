<?php

/**
 * tests if WordPress is part of the Blog description which is rarely intended
 */

$description = get_bloginfo('description');

if (preg_match('@word[ -]?press@i', $description)) {
	$check->warning("Your description <b>$description</b> contains wordpress which probably is not your intention");
} else {
	$check->success("Your description <b>$description</b> seems to be OK");
}
