<?php
// basic config data
$ConfigArray = array(
	'BASE' => 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SERVER_NAME']),
	'PATH' => dirname($_SERVER['SCRIPT_FILENAME']) . '/',
	'APP' => dirname($_SERVER['SCRIPT_FILENAME']) . '/application',
	'LIB' => dirname($_SERVER['SCRIPT_FILENAME']) . '/library',
	'ERROR' => E_ALL|E_STRICT,
	);

// configure PHP error reporting and include path
error_reporting($ConfigArray['ERROR']);
set_include_path(dirname(__FILE__) . '/../../' . PATH_SEPARATOR . $ConfigArray['LIB'] . PATH_SEPARATOR . get_include_path());

// init autoload
require_once 'A/functions/a_autoload.php';

// create config object from array
$Config = new A_DataContainer($ConfigArray);

// create HTTP objects
$Request = new A_Http_Request();
$Response = new A_Http_Response();

// Start Sessions
$Session = new A_Session();
$Session->start();  

// create registry/loader and add common objects
$Locator = new A_Locator();
$Locator->set('Config', $Config);
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);

// create router and have it modify request
$PathInfo = new A_Http_Pathinfo();
$PathInfo->run($Request);

// create mapper with base application path and default action
$Mapper = new A_Controller_Mapper($ConfigArray['APP'], array('', 'home', 'index'));
#$Mapper->setDefaultDir('default');		// set default module to 'application/default'
#$Mapper->setClassNaming('', 'ucfirst', 'Controller');	// ZF controller naming
#$Mapper->setMethodNaming('', '', 'Action');	// ZF action naming

// create and run FC with error action
$Front = new A_Controller_Front($Mapper, array('', 'error', 'index'));
$Front->run($Locator);
#echo '<pre>' . print_r($Front->getRoutes(), 1) . '</pre>';		// show what actions were called

// send response to browser
echo $Response->render();
