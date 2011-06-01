<?php
#include_once 'A/Html/Form/Radiocheckbox.php';
/**
 * Checkbox.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Checkbox
 * 
 * Generate HTML form checkbox
 */
class A_Html_Form_Checkbox extends A_Html_Form_Radiocheckbox {

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function render($attr=array(), $str='') {
		parent::mergeAttr($attr);
		if (!isset($attr['value'])) {
			$attr['value'] = $str;
		}
		$attr['type'] = 'checkbox';
		return parent::render($attr);
	}

}
