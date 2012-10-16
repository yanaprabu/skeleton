<?php
/**
 * P.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_P
 *
 * Generate HTML paragraph tag
 *
 * @package A_Html
 */
class A_Html_P extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array(), $str='')
	{
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			unset($attr['value']);
		}
		unset($attr['type']);
		return parent::render('p', $attr, $str);
	}

}
