<?php
#include_once 'A/Html/Form/Radiocheckbox.php';
/**
 * Generate HTML form checkbox
 *
 * @package A_Html
 */

class A_Html_Form_Checkbox extends A_Html_Form_Radiocheckbox {

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function render($attr=array(), $str='') {
		parent::mergeAttr($attr);
		if (!isset($attr['value'])) {
			$attr['value'] = $str;
		}
		$attr['type'] = 'checkbox';
		return parent::render($attr);
	}

}
