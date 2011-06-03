<?php
require_once 'config.php';
require_once 'A/Http/Request.php';

$Request = new A_Http_Request();
dump($Request);

?>
<form action="" method="delete">
<input type="text" name="foo" value=""/>
<input type="submit" name="go" value="go"/>
</form>
<?php
phpinfo();
?>
