<?php
/**
 * This is the simplest bootstrap and controller. 
 * No Response object is used and controllers echo their output. 
 * URLs are in the form index.php?module=&controller=home&action=index (but params are defaults so not necessary)
 */
$ConfigArray['PATH'] = dirname(__FILE__) . '/';

include $ConfigArray['PATH'] . '../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$Request = new A_Http_Request();
$Locator->set('Request', $Request);

$Front = new A_Controller_Front($ConfigArray['PATH'], array('', 'home', ''), array('', 'home', ''));
$Front->run($Locator);
