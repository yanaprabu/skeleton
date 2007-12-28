<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Text {

	/*
	 * name=string, value=string
	 */
	public function render($attr) {
		$attr['type'] = 'text';
		return A_Html_Tag::render('input', $attr);
	}

}
