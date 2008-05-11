<?php
include_once 'A/Html/Form/Radiocheckbox.php';

class A_Html_Form_Checkbox extends A_Html_Form_Radiocheckbox {

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function render($attr=array()) {
		$attr = parent::getAttr($attr);
		$attr['type'] = 'checkbox';
		return parent::render($attr);
	}


}
