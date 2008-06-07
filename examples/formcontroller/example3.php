<?php
require_once('config.php');
require_once('A/Locator.php');
require_once('A/Http/Request.php');
#require_once('A/Http/Response.php');
require_once('controllers/Form3.php');

$Locator = new A_Locator();
#$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
#$Locator->set('Response', $Response);
$Controller = new Form3($Locator);
$Controller->run($Locator);
#$Response->out();
