<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Fieldset {

	/*
	 * name=string, value=string
	 */
	public function render($attr, $str='') {
		if (isset($attr['content'])) {
			$str = $attr['content'];
			unset($attr['content']);
		}
		if (isset($attr['type'])) {
			unset($attr['type']);
		}
		return A_Html_Tag::render('fieldset', $attr, $str);
	}

}
