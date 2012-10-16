<?php
/**
 * A.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_A
 *
 * Generate HTML anchor tag
 *
 * @package A_Html
 */
class A_Html_A extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array(), $str='', $href=null)
	{
		if ($href !== null) {
			$attr['href'] = $href;
		}
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			unset($attr['value']);
		}
		unset($attr['type']);
		return A_Html_Tag::render('a', $attr, $str);
	}

}
