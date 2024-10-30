<?php

/**
 * tests if the home and the siteurl option link to the current server
 */

$home      = preg_replace('#^https?://#', '', get_option('home'));
$siteurl   = preg_replace('#^https?://#', '', get_option('siteurl'));
$host      = $_SERVER['HTTP_HOST'];
$homeOK    = substr($home, 0, strlen($host)) == $host;
$siteurlOK = substr($siteurl, 0, strlen($host)) == $host;

if ($homeOK && $siteurlOK) {
	$check->success("Both home (<b>$home</b>) and siteurl (<b>$siteurl</b>) seem to match the current host (<b>$host</b>)");
} else {
	$check->error("Home (<b>$home</b>) or siteurl (<b>$siteurl</b>) do not match the current host (<b>$host</b>)");
}
