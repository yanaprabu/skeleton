<?php

class A_Sql_Select {
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
	 * $whereExpression
	*/
	protected $whereExpression;

	/**
	 * $whereLogic
	*/
	protected $whereLogic;
	
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
	 * $havingExpression
	*/
	protected $havingExpression;

	/**
	 * $havingLogic
	*/
	protected $havingLogic;	
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
	 * Unsupported	 
	 * Do we use the previous join class?
	*/
	public function join($table1, $column1, $table2, $column2) {
		include_once('A/Sql/Join.php');
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}
	
	/**
	 * having()
	*/	
	public function having($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->having = new A_Sql_Expression($data, $value);	
		return $this;
	}
	
	/**
	 * where()
	*/
	public function where($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), 'AND');	
		return $this;
	}

	/**
	 * orWhere()
	*/	
	public function orWhere($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), 'OR');	
		return $this;		
	}

	/**
	 * groupby()
	 * Unsupported	 
	*/	
	public function groupBy($columns) {
		include_once('A/Sql/Groupby.php');
		$this->groupby = new A_Sql_Groupby($columns);	
		return $this;
	}
	
	/**
	 * orderby()
	 * Unsupported
	*/	
	public function orderBy($columns) {
		include_once('A/Sql/Orderby.php');
		$this->orderby = new A_Sql_Orderby($columns);	
		return $this;
	}

	/**
	 * render()
	*/
	public function render($db=null) {
		if (!$this->table) {
			return;
		}

		$table = $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins = '';
		if ($this->joins) {
			foreach ($this->joins as $join) {
				$joins .= $join->render();
			}
		}
		$having = $this->having ? ' HAVING ' . $this->having->render() : '';

		$where = '';
		if (count($this->where)) {
			foreach ($this->where as $key => $condition) {
				list($expression, $logical) = $condition;
				$expression->setEscapeCallback($db);
				$render = $expression->render();
				if ($key > 0) { //don't add logical on first element
					$render = ' '. strtoupper($logical) .' '. $render;
				}
				$where .= $render;
			}
			$where = ' WHERE '. $where;
		}
		$orderby = $this->orderby ? $this->orderby->render() : '';
		$groupby = $this->groupby ? $this->groupby->render() : '';
		
		return "SELECT $columns FROM $table$joins$having$where$orderby$groupby";
	}

	public function __toString() {
		return $this->render();
	}

}
