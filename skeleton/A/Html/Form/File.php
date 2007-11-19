<?php
if (! class_exists('A_Html_Tag')) include 'A/Html/Tag.php';

class A_Html_Form_File {

	/*
	 * name=string, value=string
	 */
	public function render($attr) {
		$attr['type'] = 'file';
		return A_Html_Tag::render('input', $attr);
	}

	public function getEnctype() {
		return 'enctype="multipart/form-data"' ;
	}
	public function getEnctypeAttribute() {
		$attr['enctype'] = 'multipart/form-data';
		return array('enctype' => 'multipart/form-data');
	}
}
