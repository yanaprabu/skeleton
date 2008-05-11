<?php
include_once 'A/Html/Form/Select.php';

class A_Html_Form_Selectdb extends A_Html_Form_Select {

	/*
	 * This will generate a <select> from an SQL query. Provide a DB object, SQL string, the name of the DB
	 * column to use as <option> values and one or more columns for the <option> text (concated with spaces)
	 * 
	 * db=object, sql=string, value_col=string, label_cols=array()
	 * 
	 * name=string, values=array(), $labels=array(), $selected=array(), multiple=boolean
	 */
	public function render($attr=array()) {
		$attr = parent::getAttr($attr);
		if (isset($attr['db']) && isset($attr['sql']) && isset($attr['value_col']) && isset($attr['label_cols'])) {
			$db = $attr['db'];
			$result = $db->query($attr['sql']);
			if (! $db->isError()) {
				while ($row = $result->fetchRow()) {
					$attr['values'][] = $row[$attr['value_col']];
					$label = '';
					foreach ($attr['label_cols'] as $col) {
						$label .= $row[$col] . ' ';
					}
					$attr['labels'][] = $label;
				}
			}
			unset($attr['db']);
			unset($attr['sql']);
			unset($attr['value_col']);
			unset($attr['label_cols']);
		}
		
		return parent::render($attr);
	}

}
