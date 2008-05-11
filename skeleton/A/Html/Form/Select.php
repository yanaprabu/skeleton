<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Select extends A_Html_Tag {

	/*
	 * name=string, values=array(), $labels=array(), $selected=array(), multiple=boolean
	 */
	public function render($attr=array()) {
		$attr = parent::getAttr($attr);
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

		return parent::render('select', $attr, $str);
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
