<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Session.php');

$test = preg_replace('/[^a-zA-Z0-9\ ]/', '', isset($_REQUEST['test']) ? $_REQUEST['test']  : null);
$expire = intval(isset($_REQUEST['expire']) ? $_REQUEST['expire']  : 0);

$session = new A_Session();
if ($test && $expire) {
	$session->set('test', $test, $expire);
}

if ($expire) {
	$counter = 0;
} else {
	$counter = $session->get('counter', 0);
}
$session->set('counter', ++$counter);

?>
<p>Submit form to set value with expiration count, then click refresh to expure.</p>
<form action="" method="post">
<input type="hidden" name="destroy" value=""/>
<p>Set value to <input type="text" name="test" value="foo"/></p>
<p>To expire in <input type="text" name="expire" value="5"/> requests. </p>
<p><input type="submit" name="set" value="set"/></p>
<p><a href="?destroy=">refresh</a> <a href="?destroy=yes">destroy</a></p>
<?php
echo '<pre>' . print_r($_SESSION, true) . '</pre>';

if (isset($_REQUEST['destroy']) && ($_REQUEST['destroy'] == 'yes')) {
	$session->destroy();
}