<html>
<body>
<?php
include '../../A/Locator.php';

$path = dirname(__FILE__) . '/includes';
echo "path=$path<br/>";

$locator = new A_Locator();
$locator->autoload();
echo "A_Locator autoload()<br/>";

$locator->setDir($path, 'Foo');
echo "A_Locator setDir() to load classes in namespace Foo_ or \\Foo\\ from $path/<br/>";

$regex = '/^Foo.*/';
$locator->setDir("$path/Foo", $regex);
echo "A_Locator setDir() to load classes in matching regex '$regex' from $path/Foo/<br/>";

/*
$duration = new A_DateTime_Duration();
if ($duration) echo "A_DateTime_Duration autoloaded<br/>";
$bar = new Bar();
$foobar = new Foo_Bar();
*/

$classes = array('FooBar', 'Foo_Bar', '\Foo\BarNS', '\Foo\Bar\BazNS', );
foreach ($classes as $class) {
	echo "Instantiate $class<br/>";
	$foobar = new $class();
	if (class_exists($class)) {
		echo "$class autoloaded<br/>";
	}
}
?>
</body>
</html>