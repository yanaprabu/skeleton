<?php

ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');

function dump($var, $name='') {
	echo $name . '<div style="width:25%;background:#fff;opacity: 0.7;"><pre>' . print_r($var, true) . '</pre></div>';
}

// basic config data
$ConfigArray = array(
	'BASE' => 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SERVER_NAME']),
	'PATH' => dirname($_SERVER['SCRIPT_FILENAME']) . '/',
	'APP' => dirname($_SERVER['SCRIPT_FILENAME']) . '/application/',
	'LIB' => dirname($_SERVER['SCRIPT_FILENAME']) . '/library',
	'ERROR' => E_ALL|E_STRICT,
	);
	
// Error reporting and include path
error_reporting($ConfigArray['ERROR']);
set_include_path(dirname(__FILE__) . '/../../' . PATH_SEPARATOR . $ConfigArray['LIB'] . PATH_SEPARATOR  . get_include_path() );

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
$UserSession = new A_User_Session($Session);

// create registry/loader and add common objects
$Locator = new A_Locator();
$Locator->set('Config', $Config);
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);
$Locator->set('UserSession', $UserSession);

// create router and have it modify request
$map = array(
	'' => array(
		0 => array('name'=>'controller', 'default'=>'home'),
		1 => array('name'=>'action', 'default'=>'run'),
		),
	);
$PathInfo = new A_Http_Pathinfo($map, false);
$PathInfo->run($Request);

// create mapper with base application path and default action
$Mapper = new A_Controller_Mapper($ConfigArray['APP'], array('', 'home', 'run'));

// create and run FC with error action
$Controller = new A_Controller_Front($Mapper, array('', 'error', 'run'));

// add filter to run if Action implement a denyAccess() method
include_once('application/helpers/AccessCheck.php');
$Controller->addPreFilter(new A_Controller_Front_Premethod('denyAccess', new AccessCheck($UserSession), $Locator));

$Controller->run($Locator);//dump($Controller);
#echo '<pre>' . print_r($Front->getRoutes(), 1) . '</pre>';		// show what actions were called

// send response to browser
echo $Response->render();

