<?php
/**
 * Generate SQL joins 
 * 
 * @package A_Sql 
 */

class A_Sql_Join {
	protected $type = '';
	protected $table1 = '';
	protected $table2 = '';
	protected $on = null;
	
	public function __construct($table1=null, $table2=null, $type='INNER') {
		if ($table1 && $table2) {
			$this->join($table1, $table2, $type);
		} 
	}
	
	public function join($table1, $table2, $type='INNER') {
		$type = strtoupper($type);
		$this->type = $type;
		$this->table1 = $table1;
		$this->table2 = $table2;
		$this->on = null;
	}
	
	public function on($argument1, $argument2=null, $argument3=null) {
		if (! $this->table1) { //no join has been set yet
			return;
		}
		if (!$this->on) {
			#require_once 'A/Sql/LogicalList.php';						
			$this->on = new A_Sql_LogicalList();
			$this->on->setEscape(false);
		}		
		if (is_array($argument1)) {
			if (!count($argument1)) {  //empty array of expressions was passed
				return;
			}
			foreach($argument1 as $column1 => $column2) {
				// check if the is a quoted string rather than a column name
				if (substr($column1, 0, 1) != "'") {
					$column1 = $this->prependTableAlias($this->table1, $column1);
				}
				// check if the is a quoted string rather than a column name
				if (substr($column2, 0, 1) != "'") {
					$column2 = $this->prependTableAlias($this->table2, $column2);
				}
				$this->on->addExpression($column1, $column2);
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
				$argument1 = $this->prependTableAlias($this->table1, $argument1);
			}
			if (substr($argument2, 0, 1) != "'") {
				$argument2 = $this->prependTableAlias($this->table2, $argument2);
			}
			$this->on->addExpression($logic, $argument1, $argument2);
		}
	}

	public function render() {
		$return = '';
		if ($this->table1) {
			$on = $this->on ? ' ON '. $this->on->render() : '';
			$type = $this->type ? ' '. $this->type : '';
			$return .= "$type JOIN {$this->table1}$on";
		}
		return $return;
	}

	protected function prependTableAlias($alias, $table) {
		if (!strpos($table, '.')) { //already an alias
			return $alias .'.'. $table;
		}
		return $table;
	}


	protected function getTables() {
		$tables = array();
		if ($table1) {
			$tables[] = $this->table1;
			if ($table2) {
				$tables[] = $this->table2;
			}
		}
		return $tables;
	}


	public function __toString() {
		return $this->render();
	}
}