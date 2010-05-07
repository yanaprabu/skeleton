<?php

$ConfigArray = array(
'DBDSN' => array(
	'hostspec' => "localhost",
	'database' => "skeleton",
	'username' => "skeleton",
	'password' => "skeleton",
	),
'DBDSN_SQLITE' => array(
	'filename'=>'database.sqlite',
	'mode'=>0666
	),
); 

ini_set('error_reporting', E_ALL);
#ini_set('include_path', dirname(__FILE__) . '/../../' . PATH_SEPARATOR . ini_get('include_path'));
include dirname(__FILE__) . '/../../A/autoload.php';

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
}
