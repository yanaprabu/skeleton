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
			unset($attr['value']);
		}
		unset($attr['type']);
		return parent::render('span', $attr, $str);
	}

}
