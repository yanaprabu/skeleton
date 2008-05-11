<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Password extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		$attr['type'] = 'password';
		return parent::render('input', $attr);
	}

}
