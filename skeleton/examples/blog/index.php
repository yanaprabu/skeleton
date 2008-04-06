<?php
/* error settings */
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../');

include 'config.php';
include 'A/DL.php';
include 'A/Locator.php';
include 'A/Http/Request.php';
include 'A/Http/Response.php';
include 'A/Http/PathInfo.php';
include 'A/Controller/Front.php';
include 'A/Controller/Mapper.php';
include 'A/Controller/Action.php';
include 'A/Template/Strreplace.php';
include 'A/Template/Include.php';
require_once('A/Session.php');
include 'A/User/Session.php';
include 'A/User/Access.php';

$Locator = new A_Locator();
$Response = new A_Http_Response();
$Request = new A_Http_Request();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

/* Added 23/3 */
$Session = new A_Session();
$Session->start();
$UserSession = new A_User_Session($Session);
$Locator->set('UserSession', $UserSession);

/* Map request */
$map = array(
/*
   '' => array(
		'module',
        'controller',
        'action',
        ), 
*/
	'' => array(
		'controller',
		'action',
		),
   'blog' => array(  
        '' => array(
            array('name'=>'module','default'=>'blog'), 
            array('name'=>'controller','default'=>'index'),
            array('name'=>'action','default'=>'run'),
            ),
        ),

    'admin' => array(
        '' => array(
            //'module',
			array('name'=>'module','default'=>'admin'), 
            array('name'=>'controller','default'=>'admin'),
            array('name'=>'action','default'=>'run'),
            ),
        ),

    );

$Mapper = new A_Http_PathInfo($map);
$Mapper->run($Request); //dump($Request);

$Action = new A_DL('', 'index', 'run');
$ErrorAction = new A_DL('', 'error', 'run');

$Mapper = new A_Controller_Mapper(dirname(__FILE__) . '/app/', $Action);
//$Mapper->setDefaultDir('blog');

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->addPreMethod('denyAccess', new A_DL('', 'signin', 'run')); //dump($Controller);
$Controller->run($Locator);

//dump($Response);

if (! $Response->hasRenderer()) { 
    // create a page renderer and load the outer layout page template
    $Template = new A_Template_Include($ConfigArray['APP'] . 'templates/main.php');
    $Template->set('BASE', $ConfigArray['BASE']);
    
    $Response->setRenderer($Template);
    // get the layout specified by the Action
    $Layout_name = $Response->get('layout');
    if (! $Layout_name) {
        $Layout_name = 'standardlayout';    // or use the default
    }
    $Layout = new A_Template_Include($ConfigArray['APP'] . 'templates/layout/' . $Layout_name . '.php');
    // set the two possible columns
	$Layout->set('maincontent', $Response->get('maincontent'));
    $Layout->set('subcontent', $Response->get('subcontent'));

    // render the sub-layout as the content area of the main outer layout
    $Response->set('content', $Layout->render());
} 

$Response->out();
?>