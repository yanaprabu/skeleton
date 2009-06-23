<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/DL.php');
require_once('A/Locator.php');
require_once('A/Http/Request.php');
require_once('A/Http/Response.php');
require_once('A/Controller/Front.php');
require_once('A/Controller/Mapper.php');

$Locator = new A_Locator();
$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
$Locator->set('Response', $Response);

$DefaultAction = array('', 'home', 'run');
$ErrorAction = 'error';

$Mapper = new A_Controller_Mapper('', $DefaultAction);
#$Mapper->setParams('action', '');		// add this line to run 0.3.x code 
#$Mapper->setDefaultMethod('execute');	// add this line to run 0.4.x and 0.3.x code
#$Mapper->setDefaultDir('default');		// add this for a default module directory

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->run($Locator);

$Response->out();
?>