<?php

/**
 * tests if all plugins are activated
 */

$active   = get_option('active_plugins');
$inactive = get_plugins();

foreach($active as $key) {
	unset($inactive[$key]);
}

$numberOf = count($inactive);

if ($numberOf) {
	if ($numberOf == 1) {
		$check->warning("There is <b>one deactivated plugin</b>. Is this intended?");
	} else {
		$check->warning("There are <b>$numberOf deactivated plugins</b>. Is this intended?");
	}
	$output = array();
	foreach($inactive as $key => $item) {
		$output[] = '<strong>' . $item['Name'] . '</strong> (' . $key . ')';
	}
	$check->showList($output);
} else {
	$check->success("All plugins are activated");
}
