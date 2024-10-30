<?php

/**
 * tests the template on the existence of the phrases “lorem” and “example”
 */

if (!function_exists('exec')) {
	$check->warning("The execution of <b>exec</b> is not allowed on this server");
	return;
}

$PATH = escapeshellarg(ABSPATH . 'wp-content/themes/');

$command = 'find ' . $PATH . ' -name "*" | xargs egrep -i "lorem" | fgrep -v ".svn/text-base"';

exec($command, $result);

$count = count($result);

if ($count) {
	if ($count == 1) {
		$check->error("There is one line left with filler text");
	} else {
		$check->error("There are " . $count . " line left with filler text");
	}
	$list = array();
	foreach($result as $line) {
		$list[] = preg_replace('#^([^:]+)#', '<b>$1</b>', htmlspecialchars(str_replace($PATH, '', $line)));
	}
	$check->showList($list);
} else {
	$check->success("No filler text found");
}
