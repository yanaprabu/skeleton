<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');

require 'config.php';
require 'A/Application.php';


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
echo $App->run();dump($App);
