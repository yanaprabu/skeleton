<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Label {

	/*
	 * name=string, value=string
	 */
	public function render($attr, $content=null) {
		return A_Html_Tag::render('label', $attr, $content);
	}

}
