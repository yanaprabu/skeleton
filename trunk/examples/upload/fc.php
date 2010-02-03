<?php
error_reporting(E_ALL);
require 'config.php';
require dirname(__FILE__) . '/../../A/Locator.php';

$Locator = new A_Locator();
$Locator->autoload();

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

$ErrorAction = array('', 'upload-files', 'index');
$Mapper = new A_Controller_Mapper('', $ErrorAction);

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->run($Locator);

$Response->out();
?>