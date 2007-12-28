<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Reset {

	/*
	 * name=string, value=string
	 */
	public function render($attr) {
		$attr['type'] = 'reset';
		return A_Html_Tag::render('input', $attr);
	}

}
