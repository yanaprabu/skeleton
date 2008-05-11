<?php
include_once 'A/Html/Form/Radiocheckbox.php';

class A_Html_Form_Radio extends A_Html_Form_Radiocheckbox {

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function render($attr=array()) {
		$attr['type'] = 'radio';
		return parent::render($attr);
	}

}
