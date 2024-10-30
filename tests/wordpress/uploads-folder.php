<?php

/**
 * tests if the upload directory and all of its subdirectories are writable
 */

$uploads = wp_upload_dir();
$error   = @$uploads['error'];
$baseDir = @$uploads['basedir'];

if ($error) {
	$check->error("The function wp_upload_dir returned an error: $error");
}
elseif (!$baseDir || !is_dir($baseDir)) {
	$check->error("Couldn't find any upload directory.");
} else {

	$wrongChmodDirs = array();
	$wrongChmodRoot = false;
	
	if ((fileperms($baseDir) & 511) != 511) {
		$wrongChmodDirs[] = $baseDir;
		$wrongChmodRoot   = true;
	}
	
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir), RecursiveIteratorIterator::CHILD_FIRST);

	foreach ($iterator as $item) {
		
		if ($item->isDir() && !preg_match('/\/\./', $item->getPathname())) {
			$dir = $item->getPathname();
			if ((fileperms($dir) & 511) != 511) {
				$wrongChmodDirs[] = $dir;
			}
		}
	}
	
	if (count($wrongChmodDirs)) {
		$check->error("The upload directory or at least one of its childs has another file mode than <b>777</b>. Therefore it might not be writeable.");
		$check->showList($wrongChmodDirs);
	} else {
		$check->success("The upload directory exists and has the file mode <b>777</b>, as well as all subdirectories");
	}
}
