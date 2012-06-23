<?php
/**
 * File.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_File
 *
 * Generate HTML form file input
 *
 * @package A_Html
 */
class A_Html_Form_File extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type' => 'file'));
		return parent::render('input', $attr);
	}

	public function getEnctype()
	{
		return 'enctype="multipart/form-data"' ;
	}

	public function getEnctypeAttribute()
	{
		return array('enctype' => 'multipart/form-data');
	}

}
