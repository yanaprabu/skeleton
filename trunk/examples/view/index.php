<?php
/* error settings */
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');

include 'config.php';

include 'A/Locator.php';
include 'A/Http/Request.php';
include 'A/Http/Response.php';
include 'A/Controller/Front.php';
include 'A/Controller/Mapper.php';
include 'A/Controller/Action.php';
include 'A/Template/Strreplace.php';

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator = new A_Locator();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
 
$DefaultAction = array('', 'example', 'index');
$ErrorAction = array('', 'error', 'index');
$Mapper = new A_Controller_Mapper($ConfigArray['APP'], $DefaultAction);

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->run($Locator);

//$Controller->addPreMethod(array('', 'signin', 'index'));
//$Controller->run($Locator);
 
if (! $Response->hasRenderer()) {
    // create a page renderer and load the outer layout page template
    $Template = new A_Template_Strreplace($ConfigArray['APP'] . 'templates/main.html');
    $Response->setRenderer($Template);
   
    // get the layout specified by the Action
    $Layout_name = $Response->get('layout');
    if (! $Layout_name) {
        $Layout_name = 'standardlayout';    // or use the default
    }
    // load the layout for just the content area
    $Layout = new A_Template_Strreplace($ConfigArray['APP'] . 'templates/layout/' . $Layout_name . '.html');
    // set the two possible columns
    $Layout->set('maincontent', $Response->get('maincontent'));
    $Layout->set('rightcol', $Response->get('rightcol'));
    // render the sub-layout as the content area of the main outer layout
    $Response->set('content', $Layout->render());

}
// send response
echo $Response->render();