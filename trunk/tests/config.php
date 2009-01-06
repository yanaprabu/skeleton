<?php

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../' . PATH_SEPARATOR . './include');

// Path leads to simpletest directory made by svn:externals
define('SIMPLETESTDIR', '../simpletest/');

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
}
