<?php
include_once 'A/Html/Tag.php';

class A_Html_P {

	/*
	 * name=string, value=string
	 */
	public function render($attr, $str='') {
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			unset($attr['value']);
		}
		if (isset($attr['type'])) {
			unset($attr['type']);
		}
		return A_Html_Tag::render('p', $attr, $str);
	}

}
