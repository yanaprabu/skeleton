<?php
include_once 'A/Html/Tag.php';

class A_Html_Form_Radiocheckbox extends A_Html_Tag {

	public function render($attr=array()) {
		$attr = parent::getAttr($attr);
		if (isset($attr['value'])) {
			if (! is_array($attr['value'])) {
				$attr['value'] = array($attr['value']);
			}
		} else {
			$attr['value'] = array();
		}
		if (! isset($attr['labels']) || ! $attr['labels']) {
			$attr['labels'] =& $attr['values'];
		}
		if ($attr['type'] == 'checkbox') {
			$attr['name'] .= '[]';				// checkboxes send array
		}
		if (! isset($attr['separator'])) {
			$attr['separator'] = '';
		}

		$options = array();
		$n = count($attr['values']);
		for ($i=0; $i<$n; ++$i) {
			$options[$i] = '<input type="' . $attr['type'] . '" name="' . $attr['name'] . '" value="' . $attr['values'][$i] . '"';
			if (in_array($attr['values'][$i], $attr['value'])) {
				$options[$i] .= ' checked="checked"';
			}
			$options[$i] .= '>' . (isset($attr['labels'][$i]) ? $attr['labels'][$i] : '');
		}

		return implode($attr['separator'], $options);
	}

}
