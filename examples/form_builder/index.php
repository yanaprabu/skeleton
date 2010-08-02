<?php
include dirname(__FILE__) . '/../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$Locator->set('Request', new A_Http_Request());
$Response = new A_Http_Response();
$Response->setTemplate('layout');
$Locator->set('Response', $Response);

$Front = new A_Controller_Front('', array('', 'error', ''), array('builder'));
$Front->run($Locator);

echo $Response->render();
