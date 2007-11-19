<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Session.php');

session_start();

$session1 = new A_Session();
$test = $session1->get('test');
$session1->set('test', ++$test);
echo "test=$test<br/>";
$session1->set('one.two', 'hi1');
$session1->set('one.three.six', 'hi2');
$session1->set('one.three.four', 'hi3');
$session1->set('one.five', 'hi4');

$session2 = new A_Session();
$test = $session2->get('test');
echo "test=$test<br/>";

if ($session1 === $session2) {
	echo "session1 === session2<br/>";
}
if ($session1 == $session2) {
	echo "session1 == session2<br/>";
}

echo '<pre>' . print_r($_SESSION, true) . '</pre>';

?>