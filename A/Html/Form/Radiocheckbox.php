<?php
/**
 * Radiocheckbox.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Form_Button
 * 
 * Base class to generate HTML form radio buttons and checkboxes
 */
class A_Html_Form_Radiocheckbox extends A_Html_Tag implements A_Renderer
{

	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		// normalize values to an array of values for checkboxes
		if (isset($attr['value'])) {
			if (!is_array($attr['value'])) {
				$attr['value'] = array($attr['value']);
			}
		} else {
			$attr['value'] = array();
		}
		// use values as labels if no labels
		if (!isset($attr['labels']) || !$attr['labels']) {
			$attr['labels'] =& $attr['values'];
		}
		if (($attr['type'] == 'checkbox') && isset($attr['name']) && $attr['name']) {
			if (substr($attr['name'], -2) != '[]') {
				$attr['name'] .= '[]';				// checkboxes send array
			}
		}
		if (!isset($attr['separator'])) {
			$attr['separator'] = '';
		}
		// if single value instead of array of values then make single tag
		if (!isset($attr['values'])) {
			$attr['values'][0] = $attr['value'][0];
			unset($attr['value']);
		}
		$value = isset($attr['value']) ? $attr['value'] : array();
		unset($attr['value']);
		$values = isset($attr['values']) ? $attr['values'] : array();
		parent::removeAttr($attr, 'values');
		$labels = isset($attr['labels']) ? $attr['labels'] : array();
		parent::removeAttr($attr, 'labels');
		$separator = isset($attr['separator']) ? $attr['separator'] :'';
		parent::removeAttr($attr, 'separator');
		$options = array();
		$n = count($values);
		for ($i = 0; $i < $n; $i++) {
			$attr['value'] = $values[$i];
			if (in_array($values[$i], $value)) {
				$attr['checked'] = "checked";
			} else {
				unset($attr['checked']);
			}
			$options[$i] = parent::render('input', $attr) . (isset($labels[$i]) ? $labels[$i] : '');
		}

		return implode($separator, $options);
	}

}
