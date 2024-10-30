<?php

/**
 * tests if all keys and salts are set
 */

$salts = array(
	'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY',
	'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'
);

$invalid = array();

foreach($salts as $salt) {
	$test = preg_replace('@(.)\1+@', '$1', constant($salt));
	if (strlen($test) < 3) { // seems to be a placeholder
		$invalid[] = '<b>' . htmlspecialchars($salt) . '</b>';
	}
}

if (count($invalid) > 1) {
	$check->error("The constants " . implode(', ', $invalid). " are not set");
} elseif (count($invalid) == 1) {
	$check->error("The constant {$invalid[0]} is not set");
} else {
	$check->success("All salt constants seem to be OK");
}
