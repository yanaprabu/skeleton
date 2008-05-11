<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Label extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array(), $content=null) {
		$attr = parent::getAttr($attr);
		if (! $content && isset($attr['content'])) {
			$content = $attr['content'];
			unset($attr['content']);
		}
		return A_Html_Tag::render('label', $attr, $content);
	}

}
