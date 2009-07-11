<?php
ini_set('error_reporting', E_ALL);
ini_set('include_path', dirname(__FILE__) . '/../../' . PATH_SEPARATOR . ini_get('include_path'));

function debug($var, $name='') {
	echo $name . '<pre>' . print_r($var, true) . '</pre>';
}
?>