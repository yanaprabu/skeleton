<?php
ini_set('error_reporting', E_ALL);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../');

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

function dump($var, $name='') {
	echo '<div style="position:absolute;top:0;right:0;width:500px;background:#fff;border:1px solid #ddd;padding:10px;"';
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
	echo '</div>';
}
?>