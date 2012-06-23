<?php
/**
 * Reset.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Reset
 *
 * Generate HTML form reset button
 *
 * @package A_Html
 */
class A_Html_Form_Reset extends A_Html_Tag implements A_Renderer
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
