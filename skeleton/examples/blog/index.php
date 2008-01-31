<?php
/* error settings */
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');

include 'config.php';
include 'A/DL.php';
include 'A/Locator.php';
include 'A/Http/Request.php';
include 'A/Http/Response.php';
include 'A/Http/PathInfo.php';
include 'A/Controller/Front.php';
include 'A/Controller/Mapper.php';
include 'A/Controller/Action.php';

$Locator = new A_Locator();
$Response = new A_Http_Response();
$Request = new A_Http_Request();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

/* Map request */
$map = array(
	'' => array(
		0 => array('name'=>'controller', 'default'=>'home'),
		1 => array('name'=>'action', 'default'=>'run'),
		2 => array('name'=>'id','default'=>''),
		),
	
	'module1' => array(   // if 'module1' is found in the first element of PATH_INFO use the map below
		0 => array('name'=>'module', 'default'=>'module1'),
		1 => array('name'=>'controller', 'default'=>'example'),
		2 => array('name'=>'action', 'default'=>'run'),
		3 => array('name'=>'id','default'=>'today'),
		),
	'admin' => array(	// if 'admin' is found in the first element of PATH_INFO use the map below
		'' => array(
			'module',
			'controller',
			'action',
			'id',
			),
		),
	);
$Mapper = new A_Http_PathInfo($map);
$Mapper->run($Request);

$DefaultAction = new A_DL('', 'home', 'run');
$ErrorAction = new A_DL('', 'error', 'run');

$Mapper = new A_Controller_Mapper(dirname(__FILE__) . '/app/', $DefaultAction);

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->run($Locator);

$Response->out();
?>