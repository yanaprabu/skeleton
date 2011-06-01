<?php
/**
 * Label.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Label
 * 
 * Generate HTML form label tag
 */
class A_Html_Form_Label extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array(), $str='') {	// $str not null to force end tag
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			parent::removeAttr($attr, 'value');
		}
		parent::removeAttr($attr, 'type');
		// label uses for instead of name because it refers to another field
		if (! isset($attr['for']) && isset($attr['name'])) {
			$attr['for'] = $attr['name'];
			parent::removeAttr($attr, 'name');
		}
		return A_Html_Tag::render('label', $attr, $str);
	}

}
