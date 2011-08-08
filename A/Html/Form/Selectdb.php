<?php
/**
 * Selectdb.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Selectdb
 * 
 * Generate HTML form select from database query data
 * This will generate a <select> from an SQL query. Provide a DB object, SQL string, the name of the DB
 * column to use as <option> values and one or more columns for the <option> text (concated with spaces)
 * 
 * db=object, sql=string, value_col=string, label_cols=array()
 * 
 * Or provide a callable array to a object/method. Parameters for the method can be provided,
 * 
 * model=array(object, method), model_params=array(), value_col=string, label_cols=array()
 * 
 * The interface to A_Html_Form_Select is:
 * 
 * name=string, values=array(), $labels=array(), $selected=array(), multiple=boolean
 
 * @package A_Html
 */
class A_Html_Form_Selectdb extends A_Html_Form_Select
{

	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		if (isset($attr['value_col']) && isset($attr['label_cols'])) {
			$rows = array();
			if (isset($attr['db']) && isset($attr['sql'])) {
				$db = $attr['db'];
				$result = $db->query($attr['sql']);
				if (!$db->isError()) {
					while ($row = $result->fetchRow()) {
						$rows[] = $row;
					}
				}
			} elseif (is_array($attr['model'])) {
				$rows = call_user_func_array($attr['model'], isset($attr['model_params']) ? $attr['model_params'] : array());
			}
			if ($rows) {
				if (is_string($attr['label_cols'])) {
					$attr['label_cols'] = explode('|', $attr['label_cols']);
				}
				foreach ($rows as $row) {
					$attr['values'][] = $row[$attr['value_col']];
					$label = '';
					foreach ($attr['label_cols'] as $col) {
						$label .= $row[$col] . ' ';
					}
					$attr['labels'][] = $label;
				}	
			}
			$this->removeAttr($attr, 'model');
			$this->removeAttr($attr, 'db');
			$this->removeAttr($attr, 'sql');
			$this->removeAttr($attr, 'value_col');
			$this->removeAttr($attr, 'label_cols');
		}
		
		return parent::render($attr);
	}

}
