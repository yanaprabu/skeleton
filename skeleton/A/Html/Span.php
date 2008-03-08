<?php
include_once 'A/Html/Tag.php';

class A_Html_Span {

	/*
	 * name=string, value=string
	 */
	public function render($attr) {
		if (isset($attr['value'])) {
			$str = $attr['value'];
			unset($attr['value']);
		} else {
			$str = '';
		}
		if (isset($attr['type'])) {
			unset($attr['type']);
		}
		return A_Html_Tag::render('span', $attr, $str);
	}

}
