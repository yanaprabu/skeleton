<?php
if (! class_exists('A_Html_Tag')) include 'A/Html/Tag.php';

class A_Html_Form_Select {

	/*
	 * name=string, values=array(), $labels=array(), $selected=array(), multiple=boolean
	 */
	public function render($attr) {
		$value = isset($attr['value']) ? A_Html_Form_Select::_toArray($attr['value']) : array();
		unset($attr['value']);
		$values = A_Html_Form_Select::_toArray($attr['values']);
		if (empty($attr['labels'])) {
			$attr['labels'] = $values;
		}
		unset($attr['values']);
		$labels = A_Html_Form_Select::_toArray($attr['labels']);
		unset($attr['labels']);
		
		if (isset($attr['multiple']) || (count($value) > 1)) {
			$attr['name'] .= '[]';
			$attr['multiple'] = 'multiple';		// multiple sends array
		}

		$str = '';
		$n = count($values);
		for ($i=0; $i<$n; ++$i) {
			$str .= '<option value="' . $values[$i] . '"';
			if (in_array($values[$i], $value)) {
				$str .= ' selected="selected"';
			}
			$str .= '>' . $labels[$i] . "</option>";
		}

		return A_Html_Tag::render('select', $attr, $str);
	}

	protected function _toArray($var) {
		if (isset($var)) {
			if (! is_array($var)) {
				$var = array($var);
			}
		} else {
			$var = array();
		}
		return $var;
	}

}
