<?php

// Just for debugging 
function dump($var='', $name='') {
	static $output = '';
		
	if ($var) {
#	echo '<div style="position:absolute;top:0;right:0;width:900px;background:#fff;border:1px solid #ddd;padding:10px;"';
		$output .= '<div style="clear:both;background:#fff;border:1px solid #ddd;padding:10px;">';
		$output .= $name . '<pre>' . print_r($var, 1) . '</pre>';
		$output .= '</div>';
	} else {
		echo $output;
	}
}
	

// Basic config data
$file_path = dirname($_SERVER['SCRIPT_FILENAME']);
$url_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($url_path == '\\') {
	$url_path = '';						// fix on Windows
}
$ConfigArray = array(
    'BASE' => 'http://' . $_SERVER['SERVER_NAME'] . $url_path . '/',
    'PATH' => $file_path . '/',
    'APP' => $file_path . '/app',
    'LIB' => $file_path . '/../../',     // will be $file_path . '/library'
    );

// Init autoload using Locator
require $ConfigArray['LIB'] . 'A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

// Load application config data
$ConfigIni = new A_Config_Ini('config/example.ini', 'production');
$Config = $ConfigIni->loadFile();

// import base config array into config object
$Config->import($ConfigArray);

// set error reporting from config
ini_set('error_reporting', $Config->get('ERROR'));

// Create HTTP Request object
$Request = new A_Http_Request();

// Start Sessions
$Session = new A_Session();
//$Session->start();
$UserSession = new A_User_Session($Session);

// Dbh
$dbconfig = array(
		'phptype'  => $Config->get('phptype'),
		'database' => $Config->get('database'),
		'hostspec' => $Config->get('hostspec'),
		'username' => $Config->get('username'),
		'password' => $Config->get('password')
		);
$Db = new A_Db_Pdo($dbconfig) or die ('Error: could not connect to DB');
	
// Create HTTP Response object and set default template and valuesS
$Response = new A_Http_Response();
$Response->setTemplate('mainlayout', 'module');
$Response->set('BASE', $ConfigArray['BASE']);
$Response->set('title', 'Default Title');
$Response->set('maincontent', 'Default main content.');
$Response->set('user', $UserSession);

// Add common objects to registry
$Locator->set('Config', $Config);
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);
$Locator->set('UserSession', $UserSession);
$Locator->set('Db', $Db);

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
            array('name'=>'action','default'=>'index'),
            ),
        ),
    'admin' => array(
        '' => array(
			array('name'=>'module','default'=>'admin'), 
            array('name'=>'controller','default'=>'admin'),
            array('name'=>'action','default'=>'index'),
            ),
        ),
    );
$PathInfo = new A_Http_PathInfo($map);
$PathInfo->run($Request); 

// Create mapper with base application path and default action
#$Mapper = new A_Controller_Mapper($Config->get('APP'), array('', 'index', 'index'));
#$Mapper->setDefaultDir('blog');

$Controller = new A_Controller_Front($Config->get('APP'), array('', 'error', 'index'));
$Controller->getMapper()->setDefaultDir('blog');
// Create and run FC with error action
#$Controller = new A_Controller_Front($Mapper, array('', 'error', 'index'));
$Controller->addPreFilter(new A_User_Prefilter_Group($Session, array('blog','user','login')));
$Controller->run($Locator);

// Finally, display
echo $Response->render();

dump($_SESSION, '_SESSION: ');
dump();
echo '<div style="clear:both;"><b>Included files:</b><pre>' . implode(get_included_files(), "\n") . '</pre></div>';