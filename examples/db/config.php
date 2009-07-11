<?php

$ConfigArray = array(
/*
'DBDSN' => array(
	'phptype' => "mysql",
	'hostspec' => "localhost",
	'database' => "a_todo",
	'username' => "skeleton",
	'password' => "skeleton",
	),
*/
'DBDSN' => array(
	'filename'=>'database.sqlite',
	'mode'=>0666
	),
); 

ini_set('error_reporting', E_ALL);
ini_set('include_path', dirname(__FILE__) . '/../../' . PATH_SEPARATOR . ini_get('include_path'));

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
}
?>