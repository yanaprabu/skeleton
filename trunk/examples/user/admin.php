<?php
require_once 'config.php';

// Init autoload using Locator
require  dirname(__FILE__) . '/../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Session = new A_Session();
$Session->start();
$UserSession = new A_User_Session($Session);

$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);
$Locator->set('UserSession', $UserSession);

#$UserSession->login('test', 'member|editor');
#$UserSession->logout();
#$rule = new A_Rule_UserIsLevel(15);

$UserAccess = new A_User_Access($UserSession);
$UserAccess->addRule(new A_User_Rule_Isloggedin(array('', 'login', 'index')));
#$UserAccess->addRule(new A_User_Rule_Ingroup(array('manager'), 'error'));
$UserAccess->run($Locator);

#dump($UserAccess);
#dump($Locator);

$Mapper = new A_Controller_Mapper('', array('', 'example', 'index'));
// dump($Mapper);
//dump2($Request);
$Controller = new A_Controller_Front($Mapper, array('', 'error', 'index'));
$Controller->run($Locator);
dump($Controller->getRoutes(), 'ROUTES: ');
#dump($Response);
$Response->out();
dump($_SESSION);
dump();
