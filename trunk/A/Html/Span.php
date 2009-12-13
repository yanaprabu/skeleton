<?php
#include_once 'A/Html/Tag.php';
/**
 * Generate HTML span tag
 *
 * @package A_Html
 */

class A_Html_Span extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array(), $str='') {
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			parent::removeAttr($attr, 'value');
		}
		parent::removeAttr($attr, 'type');
		return A_Html_Tag::render('span', $attr, $str);
	}

}
