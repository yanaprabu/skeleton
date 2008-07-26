<?php
include_once 'A/Html/Tag.php';
/**
 * Generate HTML form reset button
 *
 * @package A_Html
 */

class A_Html_Form_Reset extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'reset'));
		return parent::render('input', $attr);
	}

}
