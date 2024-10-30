<?php

/**
 * tests if there are any external images in the post content
 */

query_posts(array('posts_per_page' => -1, 'post_type' => 'any'));

$externalImages        = array();
$WP_CONTENT_URL_LENGTH = strlen(WP_CONTENT_URL);

while (have_posts()) {
	the_post();
	$content = get_the_content();
	
	if (preg_match_all('#<img[^>]+src\s*=\s*[\'"]([^\'"]+)#', $content, $matches)) {
		foreach($matches[1] as $item) {
			if (substr($item, 0, $WP_CONTENT_URL_LENGTH) != WP_CONTENT_URL) {
				$externalImages[] = '<b>' . htmlspecialchars(get_the_title()) . '</b>: ' . $item;
			}
		}
	}
	
}
wp_reset_query();

$count = count($externalImages);
if ($count == 0) {
	$check->success("There are no external images in the post content");
} else {
	$check->error("There are $count external images in the post content");
	$check->showList($externalImages);
}
