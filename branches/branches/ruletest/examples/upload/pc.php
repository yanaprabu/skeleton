<?php
error_reporting(E_ALL);
require 'config.php';
require dirname(__FILE__) . '/../../A/Locator.php';
require 'controllers/upload_files.php';

$Locator = new A_Locator();
$Locator->autoload();

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

$Controller = new upload_files($Locator);
$Controller->index($Locator);
$Response->out();
?>