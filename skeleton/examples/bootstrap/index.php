<?php
require_once('config.php');
require_once('A/Application.php');

class foo 
{
	public $bar = 'woohoo';
}

$App = new A_Application();

//add a random component that will be passed along into locator
$App->set('foo', new foo);

//set path to application
$App->setPath(dirname($_SERVER['SCRIPT_FILENAME']));

//output content directly to screen
echo $App->run();
