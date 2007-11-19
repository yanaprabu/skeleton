<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Locator.php');
require_once('A/Http/Request.php');
require_once('A/Http/Response.php');
require_once('controller/upload_files.php');

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator = new A_Locator();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

$Controller = new upload_files($Locator);
$Controller->run($Locator);
$Response->out();
?>