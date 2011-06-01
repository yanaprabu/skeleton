<?php
#include_once 'A/Html/Tag.php';
/**
 * Button.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Button
 * 
 * Generate HTML form button
 */
class A_Html_Form_Button extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'button', 'value'=>''));
		return parent::render('input', $attr);
	}

}
