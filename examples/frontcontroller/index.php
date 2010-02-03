<?php
error_reporting(E_ALL);
require 'config.php';
require dirname(__FILE__) . '/../../A/Locator.php';

#require_once('A/Http/Request.php');
#require_once('A/Http/Response.php');
#require_once('A/Controller/Front.php');
#require_once('A/Controller/Mapper.php');

// create Registry/Loader and initialize autoloading
$Locator = new A_Locator();
$Locator->autoload();

$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
$Locator->set('Response', $Response);

$DefaultAction = array('', 'home', 'index');
$ErrorAction = 'error';

$Mapper = new A_Controller_Mapper('', $DefaultAction);
#$Mapper->setParams('action', '');		// add this line to run 0.3.x code 
#$Mapper->setDefaultMethod('execute');	// add this line to run 0.4.x and 0.3.x code
#$Mapper->setDefaultMethod('run');		// add this line to run 0.7.x code
#$Mapper->setDefaultDir('default');		// add this for a default module directory

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
#$Controller = new A_Controller_Front('', $ErrorAction, $DefaultAction);	// have FC create Mapper
$Controller->run($Locator);

$Response->out();

if ($Controller->isError()) {
	echo '<br/><br/>Front Controller errors: ' . $Controller->getErrorMsg();
}