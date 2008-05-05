<?php

require_once 'A/Sql/Statement.php';

class A_Sql_Select extends A_Sql_Statement {
	/**
	 * $table
	*/
	protected $table;

	/**
	 * $columns
	*/
	protected $columns = null;

	/**
	 * $where
	*/
	protected $where = array();
	
	/**
	 * $joins
	 * Unsupported
	*/
	protected $joins = array();

	/**
	 * $having
	*/
	protected $having = null;

	/**
	 * $groupby
	 * Unsupported
	*/
	protected $groupby = null;			

	/** 
	 * $orderby
	 * Unsupported
	*/
	protected $orderby = null;
	
	/**
	 * columns()
	*/
	public function columns() {
		include_once('A/Sql/Columns.php');
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}

	/**
	 * from()
	*/
	public function from($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	/**
	 * join()
	*/
	public function join($table1, $column1, $table2, $column2) {
		include_once('A/Sql/Join.php');
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}
	
	/**
	 * having()
	*/	
	public function having() {
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
		if ($this->having) {
			$this->having[] = $arguments[0];
        }
        $this->having[] = $expression;    
        return $this;
    }
	
		
	/**
	 * orHaving()
	*/	
	public function orHaving($data, $value=null) {
		include_once('A/Sql/Expression.php');
        $this->escapeListeners[] = $expression = new A_Sql_Expression($data, $value);
		if ($this->having) {
			$this->having[] = 'OR';
		}
        $this->having[] = $expression;    
		return $this;	
	}
	
	/**
	 * where()
	*/
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
	
	/**
	 * orWhere()
	*/	
	public function orWhere($data, $value=null) {
		include_once('A/Sql/Expression.php');
        $this->escapeListeners[] = $expression = new A_Sql_Expression($data, $value);
		if ($this->where) {
			$this->where[] = 'OR';
		}
        $this->where[] = $expression;    
		return $this;		
	}

	/**
	 * groupby()
	*/	
	public function groupBy($columns) {
		include_once('A/Sql/Groupby.php');
		$this->groupby = new A_Sql_Groupby($columns);	
		return $this;
	}
	
	/**
	 * orderby()
	*/	
	public function orderBy($columns) {
		include_once('A/Sql/Orderby.php');
		$this->orderby = new A_Sql_Orderby($columns);	
		return $this;
	}

	/**
	 * render()
	*/
	public function render() {
		if (!$this->table) return;
		include_once 'A/Sql/LogicalList.php';
		
		$table = $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins = '';
		if (count($this->joins)) {
			foreach ($this->joins as $join) {
				$joins .= $join->render();
			}
		}
		
		$where = '';
		if ($this->where) {
			$wherelist = new A_Sql_LogicalList($this->where);
			$where = ' WHERE '. $wherelist->render();
		}
		
		$having = '';
		if ($this->having) {
			$havinglist = new A_Sql_LogicalList($this->having);
			$having = ' HAVING '. $havinglist->render();
		}
		
		$orderby = $this->orderby ? $this->orderby->render() : '';
		$groupby = $this->groupby ? $this->groupby->render() : '';
		
		return "SELECT $columns FROM $table$joins$having$where$orderby$groupby";
	}

	public function __toString() {
		return $this->render();
	}

}
