<?php
ini_set('error_reporting', E_ALL);

// initialize autoloading
include dirname(__FILE__) . '/../../A/autoload.php';

function dump($var, $name='') {
#	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
}
