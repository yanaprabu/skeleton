<ul>
<li><a href="all_tests.php">All Tests</a></li>
</ul>
<?php
function show_test_in_dir($dir) {
	$test_ext = 'Test.php';
	$length_ext = strlen($test_ext);
	echo "<ul>\n";
	foreach(glob($dir . '/*') as $testfile) {
		$filename = basename($testfile);
		if (substr($filename, -$length_ext) == $test_ext) {
			$classname = 'A_' . substr($filename, 0, strlen($filename)-$length_ext);	// remove '_test.php'
			echo "<li><a href=\"all_tests.php?test=$dir/$filename\">$classname</li>\n";
		} elseif (! in_array($filename, array('.', '..'))) {
			echo "<li>$filename\n";
			show_test_in_dir("$dir/$filename");
			echo "</li>\n";
		}
	}
	echo "</ul>\n";
}
show_test_in_dir(dirname(__FILE__) . '/unit');
?>
