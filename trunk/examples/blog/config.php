<?php
$ConfigArray = array(
// Site specific settings
'DB' => array(
    'phptype' => 'mysql',
    'hostspec' => '127.0.0.1',
    'database' => 'skeleton',
    'username' => 'skeleton',
    'password' => 'skeleton',
	),

'LIB' => '../../',
'APP' => './app/',
'BASE' => 'http://skeleton/examples/blog/',
);

function dump($var=null, $name='', $now=false) {
	static $output = '';
		
	if (!$now || func_num_args()) {
		$output .= '<div style="clear:both;background:#fff;border:1px solid #ddd;padding:10px;">';
		$output .= $name . '<pre>' . print_r($var, 1) . '</pre>';
		$output .= '</div>';
	} else {
		echo $output;
	}
}
