<?php
$ConfigArray = array(
// Site specific settings
);

ini_set('error_reporting', E_ALL);
#ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../../');

function dump($var, $name='') {
	if (is_array($var) || is_object($var)) {
		echo '<pre style="color:black;">' . $name . (isset($var) ? print_r($var, true) : '') . '</pre>';
	} else {
		echo "<span style=\"color:black;\">$name$var<br/><span>";
	}
}
