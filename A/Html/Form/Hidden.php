<?php
#include_once 'A/Html/Tag.php';
/**
 * Generate HTML form hidden input
 *
 * @package A_Html
 */

class A_Html_Form_Hidden extends A_Html_Tag {

	/*
	 * name=string, value=string, print=boolean(0|1)
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'hidden', 'value'=>''));
		if (isset($attr['print'])) {
			$print = $attr['value'];
			unset($attr['print']);
		} else {
			$print = '';
		}
		return parent::render('input', $attr) . $print;
	}

}

