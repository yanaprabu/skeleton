<?php
/**
 * Select.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Select
 *
 * Generate HTML for a <select> tag
 *
 * @package A_Html
 */
class A_Html_Form_Select extends A_Html_Tag
{

	/*
	 * name=string, values=array(), $labels=array(), $selected=array(), multiple=boolean
	 */
	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		$selected = isset($attr['value']) ? A_Html_Form_Select::_toArray($attr['value']) : array();
		unset($attr['value']);
		$values = A_Html_Form_Select::_toArray($attr['values']);
		if (empty($attr['labels'])) {
			$attr['labels'] = $values;
		}
		$this->removeAttr($attr, 'values');
		$labels = A_Html_Form_Select::_toArray($attr['labels']);
		$this->removeAttr($attr, 'labels');

		if (isset($attr['multiple']) || (count($selected) > 1)) {
			$attr['name'] .= '[]';
			$attr['multiple'] = 'multiple';		// multiple sends array
		}

		$str = '';
		foreach ($values as $value) {
			$str .= '<option value="' . $value . '"';
			if (in_array($value, $selected)) {
				$str .= ' selected="selected"';
			}
			$str .= '>' . current($labels) . "</option>";
			next($labels);
		}

		return parent::render('select', $attr, $str);
	}

	protected function _toArray($var)
	{
		if (isset($var)) {
			if (!is_array($var)) {
				$var = array($var);
			}
		} else {
			$var = array();
		}
		return $var;
	}

}
