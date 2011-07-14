<?php
/**
 * Fieldset.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Fieldset
 * 
 * Generate HTML form field set
 */
class A_Html_Form_Fieldset extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array(), $str='')	// $str not null to force end tag
	{
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			parent::removeAttr($attr, 'value');
		}
		parent::removeAttr($attr, 'type');
		return A_Html_Tag::render('fieldset', $attr, $str);
	}

}
