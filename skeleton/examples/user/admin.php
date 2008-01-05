<?php
error_reporting(E_ALL);
require_once 'config.php';
require_once 'A/DL.php';
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
$UserSession->signout();
#$rule = new A_Rule_UserIsLevel(15);
$UserAccess = new A_User_Access($UserSession);
$UserAccess->addRule(new A_User_Rule_Issignedin('signin'));
$UserAccess->addRule(new A_User_Rule_Ingroup(array('manager'), 'error'));
$UserAccess->run($Locator);

$Mapper = new A_Controller_Mapper('', new A_DL('', 'example', 'run'));
$Controller = new A_Controller_Front($Mapper, new A_DL('', 'error', 'run'));
$Controller->run($Locator);

echo "<pre>Session:\n" . print_r($_SESSION, 1) . '</pre>';
