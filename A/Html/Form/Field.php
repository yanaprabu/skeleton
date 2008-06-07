<?php

class A_Html_Form_Field {

	public function toHTML($attr, $value='') {
		$methods = array(
			'text' => 'toText',
			'password' => 'toPassword',
			'hidden' => 'toHidden',
			'submit' => 'toSubmit',
			'reset' => 'toReset',
			'textarea' => 'toTextarea',
			'select' => 'toSelect',
			'checkbox' => 'toCheckbox',
			'radio' => 'toRadio',
			);
		if ($value) {
			$attr['value'] = $value;
		}
		$method = $methods[$attr['type']];
		if ($method) {
			return A_Html_Form_Field::$method($attr);
		}
	}

	public function toTag($tag, $attr=array(), $content=null) {
		$str = "<$tag";
		foreach ($attr as $name=>$value) {
			$str .= " $name=\"$value\"";
		}
		if ($content !== null) {
			$str .= '>' . $content . "</$tag>";
		} else {
			$str .= '/>';
		}
		return $str;
	}

	/*
	 * name=string, value=string
	 */
	public function toText($attr) {
		$attr['type'] = 'text';
		return A_Html_Form_Field::toTag('input', $attr);
	}

	/*
	 * name=string, value=string
	 */
	public function toPassword($attr) {
		$attr['type'] = 'password';
		return A_Html_Form_Field::toTag('input', $attr);
	}

	/*
	 * name=string, value=string
	 */
	public function toHidden($attr) {
		$attr['type'] = 'hidden';
		return A_Html_Form_Field::toTag('input', $attr);
	}

	/*
	 * name=string, value=string
	 */
	public function toSubmit($attr) {
		$attr['type'] = 'submit';
		return A_Html_Form_Field::toTag('input', $attr);
	}

	/*
	 * name=string, value=string
	 */
	public function toReset($attr) {
		$attr['type'] = 'reset';
		return A_Html_Form_Field::toTag('input', $attr);
	}

	/*
	 * name=string, value=string
	 */
	public function toTextarea($attr) {
		if (isset($attr['value'])) {
			$str = $attr['value'];
			unset($attr['value']);
		} else {
			$str = '';
		}
		if (isset($attr['type'])) {
			unset($attr['type']);
		}
		return A_Html_Form_Field::toTag('textarea', $attr, $str);
	}

	/*
	 * name=string, values=array(), $labels=array(), $selected=array(), multiple=boolean
	 */
	public function toSelect($attr) {
		$value = isset($attr['value']) ? A_Html_Form_Field::_toArray($attr['value']) : array();
		unset($attr['value']);
		$values = A_Html_Form_Field::_toArray($attr['values']);
		if (empty($attr['labels'])) {
			$attr['labels'] = $values;
		}
		unset($attr['values']);
		$labels = A_Html_Form_Field::_toArray($attr['labels']);
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

		return A_Html_Form_Field::toTag('select', $attr, $str);
	}

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function toCheckbox($attr) {
		$attr['type'] = 'checkbox';
		return A_Html_Form_Field::toRadioCheckbox($attr);
	}

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function toRadio($attr) {
		$attr['type'] = 'radio';
		return A_Html_Form_Field::toRadioCheckbox($attr);
	}

	public function toRadioCheckbox($attr) {
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

/*
echo 'REQUEST<pre>' . print_r($_REQUEST, true) . '</pre>';
echo '<form action="" method="post">';
echo '<br/>';
echo A_Html_Form_Field::toSelect(array('name'=>'select1', 'values'=>array(1,2,3), 'labels'=>array('One','Two','Three'), 'value'=>array(1,3)));
echo '<br/>';
echo A_Html_Form_Field::toRadio(array('name'=>'radio1', 'values'=>array(1,2,3), 'labels'=>array('One','Two','Three'), 'value'=>array(2)));
echo '<br/>';
echo A_Html_Form_Field::toCheckbox(array('name'=>'checkbox1', 'values'=>array(1,2,3), 'labels'=>array('One','Two','Three'), 'value'=>array(2,3), 'separator'=>'|'));
echo '<br/>';
echo A_Html_Form_Field::toText(array('name'=>'text1', 'value'=>'text1', 'size'=>50));
echo '<br/>';
echo A_Html_Form_Field::toHidden(array('name'=>'hidden1', 'value'=>'hidden1', 'size'=>50));
echo '<br/>';
echo A_Html_Form_Field::toPassword(array('name'=>'password1', 'value'=>'password1', 'size'=>50));
echo '<br/>';
echo A_Html_Form_Field::toReset(array('name'=>'reset1', 'value'=>'reset1'));
echo '<br/>';
echo A_Html_Form_Field::toSubmit(array('name'=>'submit1', 'value'=>'submit1'));
echo '</form>';
echo '<br/>';
echo '<form action="" method="post">';
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'select', 'name'=>'select2', 'values'=>array(1,2,3), 'labels'=>array('One','Two','Three'), 'value'=>array(1,3)));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'radio', 'name'=>'radio2', 'values'=>array(1,2,3), 'labels'=>array('One','Two','Three'), 'value'=>array(2)));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'checkbox', 'name'=>'checkbox2', 'values'=>array(1,2,3), 'labels'=>array('One','Two','Three'), 'value'=>array(2,3), 'separator'=>'|'));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'text', 'name'=>'text2', 'value'=>'text2', 'size'=>50));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'hidden', 'name'=>'hidden2', 'value'=>'hidden2', 'size'=>50));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'password', 'name'=>'password2', 'value'=>'password2', 'size'=>50));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'reset', 'name'=>'reset2', 'value'=>'reset2'));
echo '<br/>';
echo A_Html_Form_Field::toHTML(array(type=>'submit', 'name'=>'submit2', 'value'=>'submit2'));
echo '</form>';
*/
