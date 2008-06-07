<?php
define('SIMPLETESTDIR', '../../../../../simpletest/');
define('TESTDIR', dirname(__FILE__) . '/');
require_once(SIMPLETESTDIR . 'simple_test.php');
require_once(SIMPLETESTDIR . 'unit_tester.php');
require_once(SIMPLETESTDIR . 'reporter.php');

$test = new GroupTest('All Test Files');
foreach(glob(dirname(__FILE__) . '/*_test.php') as $testfile) {
	$test->addTestFile($testfile);
}
if (TextReporter::inCli()) {
	$test->run(new TextReporter());
} else {
	$test->run(new HtmlReporter());
}
?>