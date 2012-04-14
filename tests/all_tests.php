<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');
require_once('config.php');
require_once(SIMPLETESTDIR . 'simpletest.php');
require_once(SIMPLETESTDIR . 'unit_tester.php');
require_once(SIMPLETESTDIR . 'web_tester.php');
require_once(SIMPLETESTDIR . 'mock_objects.php');
require_once(SIMPLETESTDIR . 'reporter.php');

// install autoloader
require_once('../A/autoload.php');

function add_test_files($test, $dir) {
	foreach(glob($dir . '*') as $testfile) {
		if (is_file($testfile) && (substr($testfile, -8) == 'Test.php')) {
			$test->addTestFile($testfile);
		} elseif (is_dir($testfile) && (substr($testfile, 0, 1) != '.')) {
			add_test_files($test, $testfile . '/');
		}
	}
}

$base_dir = dirname(__FILE__) . "/unit/";
if (isset($_GET['test']) && file_exists($base_dir . $_GET['test'])) {
	$testfile = $_GET['test'];
	$title = "Test File $testfile";
} else {
	$testfile = '';
	$title = 'All Test Files';
}
//$test = new TestSuite($title);
$test = new GroupTest($title);
if ($testfile) {
	$testfile = $base_dir . $testfile;
	if (is_file($testfile) && (substr($testfile, -8) == 'Test.php')) {
		$test->addTestFile($testfile);
	} elseif (is_dir($testfile) && (substr($testfile, 0, 1) != '.')) {
		add_test_files($test, $testfile . '/');
	}
#	$test->addTestFile($base_dir . $testfile);
} else {
	add_test_files($test, $base_dir);
}
if (TextReporter::inCli()) {
	$test->run(new TextReporter());
} else {
	$test->run(new HtmlReporter());
}
