<?php
/**
 * Button.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Button
 * 
 * Generate HTML form button
 * 
 * @package A_Html
 */
class A_Html_Form_Button extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type' => 'button', 'value' => ''));
		return parent::render('input', $attr);
	}

}
