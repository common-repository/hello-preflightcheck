<?php

/**
 * tests if the blog is available by search engines
 */

$public = get_option('blog_public');

if ($public) {
	$check->success("The site is visible to search engines");
} else {
	$check->warning("The site is not visible to search engines. Is this intended?");
}
