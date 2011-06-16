<?php
/**
 * Reset.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Reset
 * 
 * Generate HTML form reset button
 */
class A_Html_Form_Reset extends A_Html_Tag
{

	/*
	 * name=string, value=string
	 */
	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type' => 'reset'));
		return parent::render('input', $attr);
	}

}
