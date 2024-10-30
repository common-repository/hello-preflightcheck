<?php

/**
 * the class for the WordPress Hello Preflightcheck plugin. Handles the
 * initialization and integration in WordPress as well as running the
 * miscellaneous tests.
 */
final class HelloPreflightcheck {

	/**
	 * id (and directory name) of this plugin
	 */
	const ID = 'hello-preflightcheck';
	
	/**
	 * the display name of this plugin
	 */
	const NAME = 'Hello Preflightcheck';
	
	/**
	 * @var string the directory of the test scripts. Set in the execute method
	 */
	static private $testDirectory;

	/**
	 * @var string the absolute filepath
	 */
	private $path;

	/**
	 * @var string the filepath relative to the test directory
	 */
	private $localPath;

	/**
	 * @var string the filename relative to the test directory
	 */
	private $name;

	/**
	 * @var string the optional description of the test
	 * The description is the content of the first JavaDoc comment
	 */
	private $description;

	/**
	 * @var boolean is true when a test showed his output
	 * each test has to provide exactly one output (or call the ignore function)
	 */
	private $completed = false;
	
	//
	// PART I:      Integration in WordPress
	//

	/**
	 * adds references to a css and a js file to the html output
	 */
	static public function addCssAndJs() {
		wp_enqueue_style(self::ID . '_css', WP_PLUGIN_URL . '/' . self::ID . '/' . self::ID . '.css');
		wp_enqueue_script(self::ID . '_js', WP_PLUGIN_URL . '/' . self::ID . '/' . self::ID . '.js', array('jquery'));
	}

	/**
	 * adds a submenu in tht tools menu
	 */
	static public function addSubmenu() {
	    add_submenu_page(
			'tools.php',
			self::NAME,
			self::NAME,
			'activate_plugins',
			self::ID,
			array(__CLASS__, 'execute')
		);
	}
	
	//
	// PART II:     Execution
	//
	
	/**
	 * executes the tests and render the preflight check page
	 */
	static public function execute() {
		self::$testDirectory = ABSPATH . '/' . PLUGINDIR . '/' . self::ID . '/tests/';
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"><br></div><h2>' . self::NAME . '</h2>';
		
		if (!defined('ABSPATH')) {
			echo '<code>ABSPATH not defined</code>';
			return;
		}
		
		if (!defined('PLUGINDIR')) {
			echo '<code>PLUGINDIR not defined</code>';
			return;
		}
	
		echo '<ul id="' . self::ID . '">';
		
		self::runTestsInFolder(ABSPATH . '/' . PLUGINDIR . '/' . self::ID . '/tests');
		
		echo '</ul></div>';
	}
	
	/**
	 * runs all tests in the specified directory
	 * @param string the directory
	 * @param boolean specified if tests in subdirectories are executed as well
	 *
	 */
	static private function runTestsInFolder($dir, $recursive = true) {
		
		$handle = opendir($dir);
		
		while ($file = readdir($handle)) {
			$path = $dir . '/' . $file;
			if (is_dir($path) && $recursive && $file != '.' && $file != '..') {
				self::runTestsInFolder($path);
			} elseif (substr($file, -4, 4) == '.php') {
				self::executeTest($path);
			}
		}
		closedir($handle);
	}
	
	/**
	 * executes a test by including a php file in this method
	 * It is save to use variables in the tests since they are local in this method
	 * @param string the path of the function
	 */
	static private function executeTest($path) {
		$check = new self;
		$check->path        = $path;
		$check->localPath   = str_replace(self::$testDirectory, '', $path);
		$check->name        = array_pop(explode('/', $path));
		$check->description = $check->getFirstJavaDocContent();
		
		$syntaxError = $check->getSyntaxErrors();
		if (!$syntaxError) {
			include_once $path;
		} else {
			$check->fatal('Syntax error!<br />' . implode('<br />', $syntaxError));
		}
		
		if (!$check->isCompleted()) {
			$check->fatal('Attention! This test doesn’t provide any output. This is the test’s fault!');
		}
	}
	
	/**
	 * tests the php syntax of a test. Mind the different return values.
	 * @return false(!): if the syntax is ok
	 *         null: if the syntax couldn't be tested
	 *         an array of messages: if the syntax is not correct
	 */
	private function getSyntaxErrors() {
		if (function_exists('exec')) {
			$command = 'php -l ' . escapeshellarg($this->path);
			exec($command, $result, $code);
			if ($code) {
				if (strpos(implode('', $result), 'wp-admin/tools.php')) {
					// in some cases php -l seems to syntaxcheck the running
					// script instead of the given file. No solution found for
					// this beside of ignoring the result.
					return null;
				} else {
					return $code;
				}
			} else {
				return false;
			}
		} else {
			return null;
		}
	}
	
	//
	// PART III:    Output functions
	//
	
	/**
	 * displays a test result
	 * @param string the level of the result
	 * @param string the result message
	 */
	public function output($level, $message) {
		if ($this->completed && $level != 'fatal') {
			$this->fatal('Attention! This test provides more than exactly one output. This is the test’s fault!');
		} 
		
		echo '<li class="' . $level . '">';
		echo '<h3>';
		echo ucfirst(str_replace('.php', '', str_replace('-', ' ', $this->name)));
		echo '<span title="' . $this->localPath . '">';
		if ($this->description) {
			 echo htmlspecialchars($this->description);
		} else {
			echo '(' . $this->localPath . ')';
		}
		echo '</span>';
		echo '</h3>';
		echo '<p>' . $message . '</p>';
		echo '</li>';
		$this->completed = true;
	}
	
	/**
	 * displays a test result with level 'info'
	 * use this level for showing information without any evaluation
	 * @param string the result message
	 */
	public function info($message) {
		$this->output('info', $message);
	}
	
	/**
	 * displays a test result with level 'success'
	 * use this level for successful test results
	 * @param string the result message
	 */
	public function success($message) {
		$this->output('success', $message);
	}
	
	/**
	 * displays a test result with level 'warning'
	 * use this level for test results that might cause a problem
	 * @param string the result message
	 */
	public function warning($message) {
		$this->output('warning', $message);
	}
	
	/**
	 * displays a test result with level 'error'
	 * use this level for test results that definitely will cause a problem
	 * @param string the result message
	 */
	public function error($message) {
		$this->output('error', $message);
	}
	
	/**
	 * set the test to ignore. With this method you confirm as a test developer that
	 * this test doesn't provide any output.
	 */
	public function ignore() {
		$this->completed = true;
	}
	
	/**
	 * displays a list. The list is attached to the last test result
	 * There is no output replacing. The author of a test is responsible
	 * for not breaking the html
	 * @param Array a list of lines to display
	 */
	public function showList($list) {
		echo '<li class="lines"><ul>';
		foreach($list as $line) {
			echo '<li>' . $line . '</li>';
		}
		echo '</ul></li>';
	}

	/**
	 * displays a fatal error, which means that
	 * - a test doesn't show any result
	 * - a test shows more than one result
	 * - a test has a syntax error (only if exec() is available)
	 * @param string the result message
	 */
	private function fatal($message) {
		$this->output('fatal', $message);
	}
	
	/**
	 * returns if the test is completed, which means that it showed a result
	 * @return boolean the test is completed
	 */
	private function isCompleted() {
		return $this->completed;
	}
	
	//
	// PART IV:     Miscellaneous
	//
	
	/**
	 * gets a description of a test by extracting the text in the first JavaDoc
	 * style document in the test script file.
	 */
	public function getFirstJavaDocContent() {
		$content = file_get_contents($this->path);
		if (preg_match('@/\\*\\*\\s+\\*\\s*(.*)\\s*\\*/@m', $content, $matches)) {
			return $matches[1];
		} else {
			return null;
		}
	}
	
	/**
	 * greps a path by a certain pattern (a higher level helper function used in some tests).
	 * The test succeeds if no file is found.
	 * The test fails if at least one file is found. The list of matching files is displayed.
	 * The method uses 'grep' for ´pattern matching. Probably this will fail on windows systems
	 * 
	 * For an example have a look at tests/open-todos-and-fixmes-in-templates
	 * @param string the path of the directory that will be searched recursively
	 * @param string the pattern that is looked for
	 * @param options the grep options (default is -i)
	 * @param the file wildcard (default is *)
	 * @param the failClass (default is error)
	 */
	
	public function grep($path, $pattern, $options = '-i', $fileWildcard = '*', $failClass = 'error') {

		if (!function_exists('exec')) {
			$this->warning("The execution of <b>exec</b> is not allowed on this server");
			return;
		}

		$originalPath  = $path;
		$outputPattern = htmlspecialchars($pattern);
		$path          = escapeshellarg($path);
		$pattern       = escapeshellarg($pattern);
		$options       = escapeshellarg($options);
		$fileWildcard  = escapeshellarg($fileWildcard);

		$command = 'find ' . $path . ' -name ' . $fileWildcard . '  | fgrep -v ".svn/" | xargs egrep ' . $options . ' ' . $pattern . ' ';
		
		exec($command, $result);
		$count = count($result);

		if ($count) {
			if ($count == 1) {
				$this->output($failClass, "There is one line that matches <b>$outputPattern</b>");
			} else {
				$this->output($failClass, "There are " . $count . " lines that match <b>$outputPattern</b>");
			}

			$list = array();
			foreach($result as $line) {
				$list[] = preg_replace('#^([^:]+)#', '<b>$1</b>', htmlspecialchars(str_replace($originalPath, '', $line)));
			}
			$this->showList($list);

		} else {
			$this->success("No line matches <b>$outputPattern</b>");
		}
	}
	
}
