<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Password extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'password', 'value'=>''));
		return parent::render('input', $attr);
	}

}
