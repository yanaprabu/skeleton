<?php
require_once 'config.php';
require_once 'A/DL.php';
require_once 'A/DataContainer.php';
require_once 'A/Session.php';
require_once 'A/Locator.php';
require_once 'A/Http/Request.php';
require_once 'A/Http/Response.php';
require_once 'A/Http/PathInfo.php';
require_once 'A/Controller/Front.php';
require_once 'A/Controller/Mapper.php';
require_once 'A/Template/Strreplace.php';

$Locator = new A_Locator();
$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Session = new A_Session();

$Locator->set('Config', new A_DataContainer($ConfigArray));
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);

$Mapper = new A_Http_PathInfo(array('' => array('controller','action','id',)));	// array('' => array('class', 'method')));
$Mapper->run($Request);	// copies clean URL values into the Request based on the map

$Action = new A_DL('', 'home', 'run');
$Mapper = new A_Controller_Mapper($ConfigArray['APP'], $Action);        // action controllers in default 'controller' directory
#$Mapper->setDefaultMethod('execute');	// uncomment to make compatable with pre 0.4.x

$Controller = new A_Controller_Front($Mapper, $Action);
$Controller->addPreMethod('denyAccess', new A_DL('', 'signin', 'run'));   // will run this method before dispatching and forward on return true
$Controller->run($Locator);

if (! $Response->hasRenderer()) {
	$Template = new A_Template_Strreplace($ConfigArray['APP'] . 'templates/main.html');
	$Template->set('title', '');
	$Template->set('head', '');
	$Template->set('content', '');
	$Template->set('sidebar', '');
	$Response->setRenderer($Template);
}

$Response->set('BASE', $ConfigArray['BASE']);	// this renders in all templates
echo $Response->render();
