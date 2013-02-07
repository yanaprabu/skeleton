<?php
/**
 * Textarea.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Textarea
 *
 * Generate HTML form textarea input
 *
 * @package A_Html
 */
class A_Html_Form_Textarea extends A_Html_Tag
{

	/*
	 * name=string, value=string
	 */
	public function render($attr=array(), $str='')	// $str not null to force end tag
	{
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			unset($attr['value']);
		}
		unset($attr['type']);
		return parent::render('textarea', $attr, $str);
	}

}
