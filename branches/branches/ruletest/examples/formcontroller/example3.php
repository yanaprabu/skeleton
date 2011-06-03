<?php
require_once 'config.php';
require_once dirname(__FILE__) . '/../../A/Locator.php';
#require_once('A/Http/Request.php');
#require_once('A/Http/Response.php');

$Locator = new A_Locator();
$Locator->autoload();
$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
$Locator->set('Response', $Response);

require_once('controllers/Form3.php');
$Controller = new Form3($Locator);
$Controller->index($Locator);
echo $Response->render();
