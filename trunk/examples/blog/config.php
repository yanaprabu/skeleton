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
'BASE' => 'http://skeleton/examples/blog/',
);

function dump($var, $name='') {
	echo '<div style="position:absolute;top:0;right:0;width:900px;background:#fff;border:1px solid #ddd;padding:10px;"';
	echo $name . '<pre>' . print_r($var, 1) . '</pre>';
	echo '</div>';
}
?>