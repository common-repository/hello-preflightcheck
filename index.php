<?php
/*
Plugin Name:Hello Preflightcheck
Description: This plugin runs tests on the WordPress installation. It is easy to add custom tests
Version: 1.0.4
Author: Olaf Schneider for Hello Future
Author URI: http://www.hellofuture.se
*/

if (version_compare(phpversion(), '5.2.0') == -1) {
	exit('Hello Preflightcheck needs at least php 5.2.0 or higher');

} elseif (version_compare(get_bloginfo('version'), '2.7.1', '<')) {
	exit('Hello Preflightcheck needs at least WordPress 2.7.1 or higher (preferably the latest version)');
	
} else {
	include_once 'HelloPreflightcheck.php';
	
	add_action('admin_menu', array('HelloPreflightcheck', 'addSubmenu'));
	add_action('admin_init', array('HelloPreflightcheck', 'addCssAndJs'));
	
}
