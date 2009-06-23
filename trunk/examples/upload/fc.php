<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Locator.php');
require_once('A/Http/Request.php');
require_once('A/Http/Response.php');
require_once('A/Controller/Front.php');
require_once('A/Controller/Mapper.php');

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator = new A_Locator();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

$ErrorAction = array('', 'upload-files', 'index');
$Mapper = new A_Controller_Mapper('', $ErrorAction);

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->run($Locator);

$Response->out();
?>