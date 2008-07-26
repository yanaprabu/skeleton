<?php
include_once 'A/Html/Tag.php';
/**
 * Generate HTML form text input
 *
 * @package A_Html
 */

class A_Html_Form_Text extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'text', 'value'=>''));
		return parent::render('input', $attr);
	}

}
