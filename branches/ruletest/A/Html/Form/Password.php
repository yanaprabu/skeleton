<?php
/**
 * Password.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Password
 * 
 * Generate HTML form password input
 */
class A_Html_Form_Password extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array()) {
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type'=>'password', 'value'=>''));
		return parent::render('input', $attr);
	}

}
