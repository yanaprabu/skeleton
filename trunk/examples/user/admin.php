<?php
function dump($var, $name='') {
	echo '<div style="position:absolute;top:0;right:0;width:500px;background:#f2f2f2;border:1px solid #ddd;padding:10px;"';
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
	echo '</div>';
}
function dump2($var, $name='') {
	echo '<div style="position:absolute;top:0;right:500px;width:500px;background:#f2f2f2;border:1px solid #ddd;padding:10px;"';
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
	echo '</div>';
}
error_reporting(E_ALL);
require_once 'config.php';

require_once 'A/Session.php';
require_once 'A/Locator.php';
require_once 'A/Http/Request.php';
require_once 'A/Http/Response.php';
require_once 'A/Controller/Front.php';
require_once 'A/Controller/Mapper.php';
require_once 'A/User/Session.php';
require_once 'A/User/Access.php';
require_once 'A/User/Rule/Ingroup.php';
require_once 'A/User/Rule/Islevel.php';

$Session = new A_Session();
$Locator = new A_Locator();
$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

$Session->start();

$UserSession = new A_User_Session($Session);
$Locator->set('UserSession', $UserSession);

#$UserSession->signin('test', 'member|editor');
#$UserSession->signout();
#$rule = new A_Rule_UserIsLevel(15);

$UserAccess = new A_User_Access($UserSession);
$UserAccess->addRule(new A_User_Rule_Isloggedin('signin'));
#$UserAccess->addRule(new A_User_Rule_Ingroup(array('manager'), 'error'));
$UserAccess->run($Locator);

//dump($UserAccess);
dump2($Locator);

$Mapper = new A_Controller_Mapper('', array('', 'example', 'index'));// dump($Mapper);
//dump2($Request);
$Controller = new A_Controller_Front($Mapper, array('', 'error', 'index'));
$Controller->run($Locator);
//dump($Controller);
echo "<pre>Session:\n" . print_r($_SESSION, 1) . '</pre>';
dump($Response);
$Response->out();

