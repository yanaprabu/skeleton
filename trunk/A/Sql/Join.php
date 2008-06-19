<?php

class A_Sql_Join {
	protected $joins = array();
	protected $joinTypes = array('INNER', 'LEFT', 'RIGHT', 'FULL', 'CROSS', 'NATURAL');

	public function join($table1, $table2, $type) {
		if (!in_array($type, $this->joinTypes)) { //invalid join type
			return;
		}
		$this->joins[] = array('type' => strtoupper($type), 'table1' => $table1, 'table2' => $table2, 'on' => null); 
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
				$column1 = $this->prependTableAlias($join['table1'], $column1);
				$column2 = $this->prependTableAlias($join['table2'], $column2);
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
			$argument1 = $this->prependTableAlias($join['table1'], $argument1);
			$argument2 = $this->prependTableAlias($join['table2'], $argument2);
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