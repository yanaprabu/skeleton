<?php
/**
 * Span.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Span
 * 
 * Generate HTML span tag
 * 
 * @package A_Html
 */
class A_Html_Span extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array(), $str='')
	{
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			parent::removeAttr($attr, 'value');
		}
		parent::removeAttr($attr, 'type');
		return A_Html_Tag::render('span', $attr, $str);
	}

}
