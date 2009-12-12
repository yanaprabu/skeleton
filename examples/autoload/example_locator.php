<html>
<body>
<?php
include '../../A/Locator.php';

$locator = new A_Locator();
$locator->autoload();
echo "A_Locator autoload()<br/>";

$duration = new A_DateTime_Duration();
if ($duration) echo "A_DateTime_Duration autoloaded<br/>";
$bar = new Bar();
$foobar = new Foo_Bar();
?>
</body>
</html>