<?php

require_once 'A/Sql/Statement.php';

class A_Sql_Update extends A_Sql_Statement {
	protected $table;
	protected $data;
	protected $where;
	
	public function table($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function set($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->data[] = new A_Sql_Expression($data, $value);	
		$this->escapeListeners[] = end($this->data);	
		return $this;
	}
	
	public function where() {
		$numArguments = func_num_args();
		if (!$numArguments) return;
		
		include_once('A/Sql/Expression.php');
		$arguments = func_get_args();
		if ($numArguments == 1 || $numArguments == 2) {
			array_unshift($arguments, 'AND');
			if ($numArguments == 1) {
				array_push($arguments, null);
			}			
		}

		$this->escapeListeners[] = $expression = new A_Sql_Expression($arguments[1], $arguments[2]);		
		if ($this->where) {
			$this->where[] = $arguments[0];
        }
        $this->where[] = $expression;    
        return $this;
    }
	
	public function orWhere($data, $value=null) {
		include_once('A/Sql/Expression.php');
        $this->escapeListeners[] = $expression = new A_Sql_Expression($data, $value);
		if ($this->where) {
			$this->where[] = 'OR';
		}
        $this->where[] = $expression;    
		return $this;		
	}

	public function render() {
		if (!$this->table || !$this->data) return;
		include_once 'A/Sql/LogicalList.php';
		
		$table = $this->table->render();
		$joins = ''; //not implemented

		$sets = array();
		if (count($this->data)) {
			foreach ($this->data as $data) {
				$sets[] = $data->render(',');
			}
		}	
		$set = implode(', ', $sets);

		$where = '';
		if ($this->where) {
			$wherelist = new A_Sql_LogicalList($this->where);
			$where = ' WHERE '. $wherelist->render();
		}

		return "UPDATE $table SET $set$joins$where";

	}

	public function __toString() {
		return $this->render();
	}

}
