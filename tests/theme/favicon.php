<?php

/**
 * tests if some usual favicons and ios icons are available
 */

$missing = array();

$icons = array('favicon.gif', 'favicon.ico', 'apple-touch-icon.png');

foreach($icons as $icon) {
	#echo ABSPATH . $icon; die;
	if (!is_readable(ABSPATH . $icon)) {
		$missing[] = $icon;
	}
}

if (count($missing) > 1) {
	$check->warning("The icons <b>" . implode(', ', $missing) . "</b> are missing");
} elseif (count($missing) == 1) {
	$check->warning("The icon <b>{$missing[0]}</b> is missing");
} else {
	$check->success("All icons (" . implode(', ', $icons) . ") could be found");
}
