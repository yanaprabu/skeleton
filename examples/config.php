<?php

ini_set('error_reporting', E_ALL ^E_NOTICE);
#ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../');
include dirname(__FILE__) . '/../A/autoload.php';

$config = parse_ini_file ('config.ini', true);

function dump($var, $name='') {
	echo $name . '<pre>' . print_r($var, true) . '</pre>';
}

function d($var, $name='')	{
	echo '<div style="padding: 10px; margin: 10px; background-color: #eee;">';
	echo '<strong>' . ($name?'var_dump: '.$name:'') . '</strong><pre>';
	var_dump($var);
	echo '</pre></div>';
}

function p($var, $name='')	{
	echo '<div style="padding: 10px; margin: 10px; background-color: #eee;">';
	echo '<strong>' . ($name?'var_dump: '.$name:'') . '</strong><pre>';
	print_r($var, $true);
	echo '</pre></div>';
}