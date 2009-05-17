<?php
ini_set('error_reporting', E_ALL ^E_NOTICE);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../');

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, true) . '</pre>';
}
?>