<?php
if (! class_exists('A_Html_Form_Field')) include 'A/Html/Form/Field.php';

class A_Controller_Form_Field {
protected $func_param = null;
protected $db = null;
	
	public function __construct() {
	}

	public function setDB($db) {
		$this->db = $db;
	}

	public function setFunctionParam($func_param) {
		$this->func_param = $func_param;
	}

	public function toHTML($attr, $value='') {
		$methods = array(
			'print_query' => 'toQuery',
			'select_query' => 'toQuery',
			'checkbox_query' => 'toQuery',
			'radio_query' => 'toQuery',
			'function' => 'toFunction',
			'print' => 'toPrint',
			'print_hidden' => 'toPrintHidden',
			'sprintf' => 'toSprintf',
			'link' => 'toLink',
			'translate' => 'toTranslate',
			);
		if (isset($attr['type']) && isset($methods[$attr['type']])) {
			$method = $methods[$attr['type']];
		} else {
			$method = '';
		}
		if ($method) {
			if ($value) {
				$attr['value'] = $value;
			} elseif (! isset($attr['value'])) {
				$attr['value'] = '';
			}
			return A_Controller_Form_Field::$method($attr);
		} else {
			return A_Html_Form_Field::toHTML($attr, $value);
		}
	}

	/*
	 * 'type' = 'print_query' prints the query values
	 *			'select_query' creates a select from query values
	 *			'checkbox_query' creates checkboxes from query values
	 *			'radio_query' creates radios from query values
	 *
	 * 'sql' contains SQL for query
	 *
	 * 'value_field' specifices the column for values
	 *
	 * 'label_fields' specifices the column for labels. Multi column|column|column concatenated
	 *
	 * 'separator' for concatenating column values
	 */
	public function toQuery($attr) {
		if (! isset($this->db)) {
 			return 'Error: no DB. ';
		}
		switch ($attr['type']) {
		case 'print_query':
			$attr['type'] = 'print';
			$doquery = true;
			break;
		case 'select_query':
			$attr['type'] = 'select';
			$doquery = true;
			break;
		case 'checkbox_query':
			$attr['type'] = 'checkbox';
			$doquery = true;
			break;
		case 'radio_query':
			$attr['type'] = 'radio';
			$doquery = true;
			break;
		default:
			$attr['type'] = '';
		}
		if ($attr['type']) {
			$sql = $attr['sql'];
			unset ($attr['sql']);

			if (isset($attr['separator'])) {
				$separator = $attr['separator'];
			} else {
				$separator = ' ';
			}
			$val = '';
			$str = array();
			$txt = array();
// if previous query used the same sql then get data from cache rather than query again
			if (isset($this->query_cache)) {
				foreach ($this->query_cache as $query) {
					if ($query['sql'] == $sql) {
						$val = $query['val'];
						$txt = $query['txt'];
					}
				}
			}

// if data not found in the cache then query database
			if (! $val) {
				$res = $this->db->query($sql);
				if ($this->db->isError() ) {
					$this->errmsg .= $this->db->getMessage() . '. ';
				}
				unset ($val);
				unset ($txt);
				if ($attr['type'] == 'print') {
					$row = $res->fetchRow();
					$val = null;
					if (is_array($row)) {
						foreach ($row as $i) {
							if ($i) {
								$val[] = $i;
							}
						}
					}
					if ($val) {
						$str = implode($separator, $val);
					} else {
						$str = $attr['default'];	// no values from query then set default value
					}
				} else {
					$i = 0;
// multiple fields can be combined for the text label
					$textfields = explode ('|', $attr['label_fields']);
					while ($option = $res->fetchRow()){
						$val[$i] = $option[$attr['value_field']];
						$txt[$i] = '';
						foreach ($textfields as $tf) {
// concat multiple text labels
							$txt[$i] .= $option[$tf] . $separator;
						}
						++$i;
					}
				}
				$query['sql'] = $sql;
				$query['val'] = $str;
				$query['txt'] = $str;
				$this->query_cache[] = $query;
			}

// assign to form array so it works like the form types below
			if ($attr['type'] == 'print') {
				$attr['value'] = $query['val'];
				return A_Controller_Form_Field::toPrint($attr);
			} else {
				$attr['values'] = $val;
				$attr['labels'] = $txt;
				return A_Html_Form_Field::toHTML($attr);
			}
		}

	}

	public function toFunction($attr) {
		$str .= call_user_func($attr['function'], $this->func_param);
	}

	public function toPrint($attr) {
		return $attr['value'];
	}

	public function toPrintHidden($attr) {
#		$attr['hidden'];
		return $attr['value'] . A_Html_Form_Field::toHidden($attr);
	}

	public function toSprintf($attr) {
		return sprintf ($attr['format'], $attr['value']);
	}

	public function toLink($attr) {
		return $str = "<a href=\"{$attr['url']}?{$attr['param']}={$attr['value']}\">{$attr['label']}</a>";
				$checksave = true;
	}

	public function toTranslate($attr) {
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
