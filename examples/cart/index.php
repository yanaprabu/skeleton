<?php
include 'config.php';

// Init autoload using Locator
require $ConfigArray['LIB'] . 'A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$Config = new A_Collection($ConfigArray);

// Create HTTP Request object
$Request = new A_Http_Request();

// Start Sessions
$Session = new A_Session();

// Create HTTP Response object and set default template and valuesS
$Response = new A_Http_Response();
$Response->setTemplate('layoutmain');
$Response->set('BASE', $ConfigArray['BASE']);
$Response->set('title', 'Cart Example');
$Response->set('head', '');
$Response->set('content', '');

// Add common objects to registry
$Locator->set('Config', $Config);
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$Locator->set('Session', $Session);

$Pathinfo = new A_Http_Pathinfo();
$Pathinfo->run($Request); 

$Controller = new A_Controller_Front($Config->get('APP'), array('', 'product_category', 'index'));
$Controller->run($Locator);

// Finally, display
echo $Response->render();

#dump($_SESSION, '_SESSION: ');
#dump($_REQUEST, '_REQUEST: ');
dump();
#echo '<div style="clear:both;"><b>Included files:</b><pre>' . implode(get_included_files(), "\n") . '</pre></div>';