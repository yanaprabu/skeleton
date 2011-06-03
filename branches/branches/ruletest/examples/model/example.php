<?php
require 'config.php';
require dirname(__FILE__) . '/../../A/Locator.php';

$Locator = new A_Locator();
$Locator->autoload();

require_once dirname(__FILE__) . '/controllers/somecontroller.php';

$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
$Locator->set('Response', $Response);
$Controller = new Somecontroller($Locator);
$Controller->index($Locator);
echo $Response->render();
