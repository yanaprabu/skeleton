<?php
/**
 * Generate SQL joins 
 * 
 * @package A_Sql 
 */

class A_Sql_Join {
	protected $joins = array();

	public function join($table1, $table2, $type = 'INNER') {
		$type = strtoupper($type);
		$this->joins[] = array('type' => $type, 'table1' => $table1, 'table2' => $table2, 'on' => null); 
	}
	
	public function on($argument1, $argument2=null, $argument3=null) {
		$joinkey = count($this->joins)-1;
		if (!isset($this->joins[$joinkey])) { //no join has been set yet
			return;
		}
		$join = &$this->joins[$joinkey];
		if (!$join['on']) {
			require_once 'A/Sql/LogicalList.php';						
			$join['on'] = new A_Sql_LogicalList();
			$join['on']->setEscape(false);
		}		
		if (is_array($argument1)) {
			if (!count($argument1)) {  //empty array of expressions was passed
				return;
			}
			foreach($argument1 as $column1 => $column2) {
				// check if the is a quoted string rather than a column name
				if (substr($column1, 0, 1) != "'") {
					$column1 = $this->prependTableAlias($join['table1'], $column1);
				}
				// check if the is a quoted string rather than a column name
				if (substr($column2, 0, 1) != "'") {
					$column2 = $this->prependTableAlias($join['table2'], $column2);
				}
				$this->joins[$joinkey]['on']->addExpression($column1, $column2);
			}
		} else {
			//since we allow different style of parameters we must account for different
			//amount of parameters
			if ($argument3 === null && !is_array($argument2)) { 
				$logic = 'AND';
			} else {
				$logic = $argument1;
				$argument1 = $argument2;
				$argument2 = $argument3;
			}
			if (substr($argument1, 0, 1) != "'") {
				$argument1 = $this->prependTableAlias($join['table1'], $argument1);
			}
			if (substr($argument2, 0, 1) != "'") {
				$argument2 = $this->prependTableAlias($join['table2'], $argument2);
			}
			$join['on']->addExpression($logic, $argument1, $argument2);
		}
	}

	public function render() {
		$return = '';
		foreach ($this->joins as $join) {
			$on = $join['on'] ? ' ON '. $join['on']->render() : '';
			$return .= ($return ? ' ' : '') . $join['type'] .' JOIN '. $join['table1'] . $on;
		}
		return $return;
	}

	protected function prependTableAlias($alias, $table) {
		if (!strpos($table, '.')) { //already an alias
			return $alias .'.'. $table;
		}
		return $table;
	}


	public function __toString() {
		return $this->render();
	}
}