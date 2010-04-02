<?php
/**
 * Generate SQL joins 
 * 
 * @package A_Sql 
 */

class A_Sql_Join {
	protected $type = '';
	protected $table_right = '';
	protected $table_left = '';
	protected $on = null;
	
	public function __construct($table_right=null, $table_left=null, $type=null) {
		if ($table_right) {
			$this->join($table_right, $table_left, $type);
		} 
	}
	
	public function join($table_right, $table_left, $type=null) {
		$this->type = ($type !== null) ? strtoupper($type) : 'INNER';
		$this->table_right = $table_right;
		$this->table_left = $table_left;
		$this->on = null;
		return $this;
	}
	
	public function on($argument1, $argument2=null, $argument3=null) {
		if (! $this->table_right) { //no join has been set yet
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
					$column1 = $this->prependTableAlias($this->table_right, $column1);
				}
				// check if the is a quoted string rather than a column name
				if (substr($column2, 0, 1) != "'") {
					$column2 = $this->prependTableAlias($this->table_left, $column2);
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
				$argument1 = $this->prependTableAlias($this->table_right, $argument1);
			}
			if (substr($argument2, 0, 1) != "'") {
				$argument2 = $this->prependTableAlias($this->table_left, $argument2);
			}
			$this->on->addExpression($logic, $argument1, $argument2);
		}
		return $this;
	}

	public function render() {
		$return = '';
		if ($this->table_right) {
			$on = $this->on ? ' ON '. $this->on->render() : '';
			$type = $this->type ? ' '. $this->type : '';
			$return .= "$type JOIN {$this->table_right}$on";
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
		if ($this->table_right) {
			$tables[] = $this->table_right;
			if ($this->table_left) {
				$tables[] = $this->table_left;
			}
		}
		return $tables;
	}


	public function __toString() {
		return $this->render();
	}
}