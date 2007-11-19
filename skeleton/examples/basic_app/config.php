<?php
$ConfigArray = array(
// Site specific settings
'DBDSN' => array(
    'phptype' => 'mysql',
    'hostspec' => 'localhost',
    'database' => 'xxxx',
    'username' => 'xxxx',
    'password' => 'xxxx',
	),

'APP' => './app/',
'BASE' => 'http://www.mydomain.com/',
);

ini_set('error_reporting', E_ALL);
ini_set('include_path', ini_get('include_path') . 
	PATH_SEPARATOR . $ConfigArray['APP'] .
	PATH_SEPARATOR . '../../'			// this only needed for these examples
	);

function dump($var, $name='') {
	if (is_array($var) || is_object($var)) {
		echo '<pre style="color:black;">' . $name . (isset($var) ? print_r($var, true) : '') . '</pre>';
	} else {
		echo "<span style=\"color:black;\">$name$var<br/><span>";
	}
}
