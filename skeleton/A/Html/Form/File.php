<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_File extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'file'));
		return parent::render('input', $attr);
	}

	public function getEnctype() {
		return 'enctype="multipart/form-data"' ;
	}
	
	public function getEnctypeAttribute() {
		return array('enctype' => 'multipart/form-data');
	}
}
