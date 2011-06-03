<?php
ini_set('error_reporting', E_ALL);
#ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../../');

function dump($var='', $name='') {
	static $output = '';
		
	if ($var) {
		$output .= '<div style="clear:both; background:#f2f2f2; border:1px solid #ddd; padding:10px;">';
		$output .= $name . '<pre>' . print_r($var, 1) . '</pre>';
		$output .= '</div>';
	} else {
		echo $output;
	}
}
