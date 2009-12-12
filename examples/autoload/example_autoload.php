<html>
<body>
<?php
include '../../A/autoload.php';

$bar = new Bar();
$foobar = new Foo_Bar();
$locator = new A_Locator();
if (class_exists('A_Locator')) {
	echo "A_Locator autoloaded<br/>";
}
?>
</body>
</html>