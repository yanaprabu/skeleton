<ul>
<li><a href="all_tests.php">All Tests</a></li>
</ul>
<ul>
<?php
foreach(glob(dirname(__FILE__) . '/*_test.php') as $testfile) {
	$filename = basename($testfile);
	$classname = 'A_' . substr($filename, 0, strlen($filename)-9);	// remove '_test.php'
	echo "<li><a href=\"all_tests.php?test=$filename\">$classname</li>\n";
}
?>
</ul>