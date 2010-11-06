<?php
require 'config.php';
require $ConfigArray['LIB'] . 'A/Locator.php';

$Locator = new A_Locator();
$Locator->autoload();           // initializing autoloading

$Config = new A_Collection($ConfigArray);
$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Response->setTemplate('layouts/main.php');
$Response->set('BASE', $ConfigArray['BASE']);
$Response->set('title', $ConfigArray['TITLE']);
$Response->set('head', '');

$Session = new A_Session();
$UserSession = new A_User_Session($Session);

$Locator->set('Config', $Config);
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);
$Locator->set('UserSession', $UserSession);

$Router = new A_Http_Pathinfo();
$Router->run($Request);

$Mapper = new A_Controller_Mapper($ConfigArray['APP'], array('', 'home', ''));

$Controller = new A_Controller_Front($Mapper, array('', 'error', ''));
$Controller->addPreFilter(new A_User_Prefilter_Group($Session, array('','user','login')));
$Controller->run($Locator);

$Response->run($Locator);
echo $Response->render();

echo $Controller->getErrorMsg(', ');