<?php
$ConfigArray = array(
'LIB' => '../../',
'APP' => dirname($_SERVER["SCRIPT_FILENAME"]) . '/app/',
'BASE' => 'http://' . $_SERVER["SERVER_NAME"]. dirname($_SERVER['SCRIPT_NAME']) . '/',
);

function dump($var=null, $name='', $now=false) {
	static $output = '';
		
	if ($now || func_num_args()) {
		$str = '<div style="clear:both;background:#fff;border:1px solid #ddd;padding:10px;">';
		$str .= $name . '<pre>' . print_r($var, 1) . '</pre>';
		$str .= '</div>';
		if ($now) {
			echo $str;
		} else {
			$output .= $str;
		}
	} else {
		echo $output;
	}
}
