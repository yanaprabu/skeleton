<?php

// Just for debugging 
function dump($var, $name='') {
	echo '<div style="position:absolute;top:0;right:0;width:900px;background:#fff;border:1px solid #ddd;padding:10px;"';
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
	echo '</div>';
}


// Basic config data
$path = dirname($_SERVER['SCRIPT_FILENAME']);
$ConfigArray = array(
    'BASE' => 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER["SCRIPT_NAME"]) . '/',
    'PATH' => $path . '/',
    'APP' => $path . '/app',
    'LIB' => $path . '/../../',     // will be $path . '/library'
    );

// Configure PHP include path
set_include_path($ConfigArray['LIB'] . PATH_SEPARATOR . get_include_path());

// Init autoload
require_once 'A/functions/a_autoload.php';

// Load application config data
$ConfigIni = new A_Config_Ini('config/example.ini', 'production');
$Config = $ConfigIni->loadFile();

// import base config array into config object
$Config->import($ConfigArray);

// Create HTTP objects
$Request = new A_Http_Request();
$Response = new A_Http_Response();

// Start Sessions
$Session = new A_Session();
$Session->start();
$UserSession = new A_User_Session($Session);

// Create registry/loader and add common objects
$Locator = new A_Locator();
$Locator->set('Config', $Config);
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);
$Locator->set('UserSession', $UserSession);

// Create router and have it modify request
$map = array(
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
			array('name'=>'module','default'=>'admin'), 
            array('name'=>'controller','default'=>'admin'),
            array('name'=>'action','default'=>'run'),
            ),
        ),
    );
$PathInfo = new A_Http_PathInfo($map);
$PathInfo->run($Request); 

// Create mapper with base application path and default action
$Mapper = new A_Controller_Mapper($Config->get('APP'), new A_DL('', 'index', 'run'));
$Mapper->setDefaultDir('blog');

// Create and run FC with error action
$Controller = new A_Controller_Front($Mapper, new A_DL('', 'error', 'run'));
$Controller->addPreFilter('denyAccess', new A_Controller_Front_Premethod('denyAccess', new A_DL('', 'signin', 'run'), $Locator));
$Controller->run($Locator);

// Set up renderer and templates if the response doesnt have those already
if (! $Response->hasRenderer()) { 
	
    // create a page renderer and load the outer layout page template
    $Template = new A_Template_Include($ConfigArray['APP'] . '/templates/main.php');

   	$Template->set('BASE', $ConfigArray['BASE']);
    
    $Response->setRenderer($Template);
    // get the layout specified by the Action
    $Layout_name = $Response->get('layout');
    if (! $Layout_name) {
        $Layout_name = 'standardlayout';    // or use the default
    }
    $Layout = new A_Template_Include($ConfigArray['APP'] . '/templates/layout/' . $Layout_name . '.php');
    // set the two possible columns
	$Layout->set('maincontent', $Response->get('maincontent'));
    $Layout->set('subcontent', $Response->get('subcontent'));

    // render the sub-layout as the content area of the main outer layout
    $Response->set('content', $Layout->render());
} 

// Finally, display
$Response->out();
