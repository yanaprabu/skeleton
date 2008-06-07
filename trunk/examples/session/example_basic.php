<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Session.php');

$session1 = new A_Session();
$counter = $session1->get('counter', 9);
$session1->set('counter', ++$counter);
echo "Start counter with a default of 10, counter=$counter<br/>";
$session1->set('one.two', 'hi1');
$session1->set('one.three.six', 'hi2');
$session1->set('one.three.four', 'hi3');
$session1->set('one.five', 'hi4');

$session2 = new A_Session();
$counter = $session2->get('counter');
echo "Is Singleton? Second session object counter=$counter<br/>";

if ($session1 !== $session2) {
	echo "session1 !== session2<br/>";
}
if ($session1 == $session2) {
	echo "session1 == session2<br/>";
}

?>
<p><a href="?destroy=">refresh</a> <a href="?destroy=yes">destroy</a></p>
<?php
echo '<pre>' . print_r($_SESSION, true) . '</pre>';

if (isset($_REQUEST['destroy']) && ($_REQUEST['destroy'] == 'yes')) {
	$session1->destroy();
}