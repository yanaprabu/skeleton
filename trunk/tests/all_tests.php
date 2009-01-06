<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');
require_once('config.php');
require_once(SIMPLETESTDIR . 'simpletest.php');
require_once(SIMPLETESTDIR . 'unit_tester.php');
require_once(SIMPLETESTDIR . 'mock_objects.php');
require_once(SIMPLETESTDIR . 'reporter.php');

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
	$test->addTestFile($base_dir . $testfile);
} else {
	foreach(glob($base_dir . "*Test.php") as $testfile) {
		$test->addTestFile($testfile);
	}
}
if (TextReporter::inCli()) {
	$test->run(new TextReporter());
} else {
	$test->run(new HtmlReporter());
}
