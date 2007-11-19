<?php
if (! class_exists('A_Html_Tag')) include 'A/Html/Tag.php';

class A_Html_Form {

	/*
	 * name=string, value=string or renderer
	 */
	public function render($attr, $content=null) {
		if (! isset($attr['method'])) {
			$attr['method'] = 'post';
		}
		A_Html_Tag::setDefaults($attr, array('method'=>'post', 'action'=>'', ));
		return A_Html_Tag::render('form', $attr, $content);
	}

}
