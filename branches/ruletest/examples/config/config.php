<?php
ini_set('error_reporting', E_ALL);
#ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../../');


function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
}
?>