<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Button extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'button', 'value'=>''));
		return parent::render('input', $attr);
	}

}
