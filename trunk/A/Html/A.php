<?php
/**
 * A.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_A
 * 
 * Generate HTML anchor tag
 */
class A_Html_A extends A_Html_Tag
{

	public function render($attr=array(), $str='', $href=null)
	{
		if ($href !== null) {
			$attr['href'] = $href;
		}
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			parent::removeAttr($attr, 'value');
		}
		parent::removeAttr($attr, 'type');
		return A_Html_Tag::render('a', $attr, $str);
	}

}
