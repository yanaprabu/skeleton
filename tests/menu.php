<html>
<head>
<title>Test Runner Menu</title>
</head>
<body>
<ul>
<li><a href="all_tests.php">All Tests</a></li>
</ul>
<?php
function show_test_in_dir($base_dir, $dir) {
	$test_ext = 'Test.php';
	$length_ext = strlen($test_ext);
	echo "<ul>\n";
	foreach(glob("$base_dir$dir*") as $testfile) {
		$filename = basename($testfile);
		if (substr($filename, -$length_ext) == $test_ext) {									// show test scripts
			$classname = 'A_' . substr($filename, 0, strlen($filename)-$length_ext);
			echo "<li><a href=\"all_tests.php?test=$dir$filename\">$classname</a></li>\n";
		} elseif (is_dir($testfile) && ! in_array($filename, array('.', '..'))) {			// show only dirs but not ./..
			echo "<li><a href=\"all_tests.php?test=$dir$filename\">$filename</a>\n";
			show_test_in_dir($base_dir, "$dir$filename/");
			echo "</li>\n";
		}
	}
	echo "</ul>\n";
}
$base_dir = dirname(__FILE__) . "/unit/";
show_test_in_dir($base_dir, '');
?>
</body>
</html>