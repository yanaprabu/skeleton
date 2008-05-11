<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Hidden extends A_Html_Tag {

	/*
	 * name=string, value=string, print=boolean(0|1)
	 */
	public function render($attr=array()) {
		$attr['type'] = 'hidden';
		return parent::render('input', $attr) . (isset($attr['print']) ? $attr['value'] : '');
	}

}

