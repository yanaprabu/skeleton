<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Text extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		$attr['type'] = 'text';
		return parent::render('input', $attr);
	}

}
