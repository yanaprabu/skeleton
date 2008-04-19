<?php
ini_set('error_reporting', E_ALL | E_STRICT);

require_once('config.php');
require_once(SIMPLETESTDIR . 'simpletest.php');
require_once(SIMPLETESTDIR . 'unit_tester.php');
require_once(SIMPLETESTDIR . 'reporter.php');

if (isset($_GET['test']) && file_exists($_GET['test'])) {
	$testfile = $_GET['test'];
	$title = "Test File $testfile";
} else {
	$testfile = '';
	$title = 'All Test Files';
}
$test = &new GroupTest($title);
if ($testfile) {
	$test->addTestFile($_GET['test']);
} else {
	foreach(glob(dirname(__FILE__) . '/*_test.php') as $testfile) {
		$test->addTestFile($testfile);
	}
}
if (TextReporter::inCli()) {
	$test->run(new TextReporter());
} else {
	$test->run(new HtmlReporter());
}
