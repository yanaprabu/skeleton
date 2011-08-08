<?php
/**
 * Text.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Text
 * 
 * Generate HTML form text input
 * 
 * @package A_Html
 */
class A_Html_Form_Text extends A_Html_Tag implements A_Renderer
{

	/*
	 * name=string, value=string
	 */
	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type' => 'text', 'value' => ''));
		return parent::render('input', $attr);
	}

}
