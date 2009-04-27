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

function dump($var='', $name='') {
	static $output = '';
		
	if ($var) {
#	echo '<div style="position:absolute;top:0;right:0;width:900px;background:#fff;border:1px solid #ddd;padding:10px;"';
		$output .= '<div style="clear:both;background:#fff;border:1px solid #ddd;padding:10px;">';
		$output .= $name . '<pre>' . print_r($var, 1) . '</pre>';
		$output .= '</div>';
	} else {
		echo $output;
	}
}
