<?php
/**
 * Hidden.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Hidden
 * 
 * Generate HTML form hidden input
 * 
 * @package A_Html
 */
class A_Html_Form_Hidden extends A_Html_Tag implements A_Renderer
{

	/*
	 * name=string, value=string, print=boolean(0|1)
	 */
	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type' => 'hidden', 'value' => ''));
		if (isset($attr['print'])) {
			$print = $attr['value'];
			$this->removeAttr($attr, 'print');
		} else {
			$print = '';
		}
		return parent::render('input', $attr) . $print;
	}

}
