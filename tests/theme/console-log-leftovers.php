<?php

/**
 * tests if there are still console.log messages in any JavaScript file
 */

$check->grep(ABSPATH . 'wp-content/themes/', 'console\\.log', '-i', '*.js', 'warning');
