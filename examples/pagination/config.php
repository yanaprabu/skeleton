<?php
ini_set('error_reporting', E_ALL ^E_NOTICE);

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, true) . '</pre>';
}
?>