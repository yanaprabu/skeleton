<?php
error_reporting(E_ALL);

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, true) . '</pre>';
#	echo '<pre>';	var_dump($var);
}
