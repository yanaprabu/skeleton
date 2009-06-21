 <?php
include 'config.php';

include 'A/DL.php';
include 'A/Locator.php';
include 'A/Http/Request.php';
include 'A/Http/Response.php';
include 'A/Controller/Front.php';
include 'A/Controller/Mapper.php';
include 'A/Controller/Action.php';

$Locator = new A_Locator();
$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
$Locator->set('Response', $Response);

$DefaultAction = array('', 'home', 'run');
$ErrorAction = array('', 'error', 'run');

$Mapper = new A_Controller_Mapper(dirname(__FILE__) . '/app/', $DefaultAction);

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->run($Locator);

$Response->out();

/*
require_once('A/Application.php');


$App = new A_Application();

$App->setPath(dirname($_SERVER['SCRIPT_FILENAME']) . 'application/');

//output content directly to screen
echo $App->run();
#dump($App);
*/

if (isset($_SESSION)) dump($_SESSION);