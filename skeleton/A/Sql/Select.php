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
	public function having($data, $value=null, $override = 'AND') {
		include_once('A/Sql/Expression.php');
		$this->having[] = array(new A_Sql_Expression($data, $value), $override);	
		$this->escapeListeners[] = end($this->having);
		return $this;
	}
	
	/**
	 * orHaving()
	*/	
	public function orHaving($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->having[] = array(new A_Sql_Expression($data, $value), 'OR');	
		$this->escapeListeners[] = end($this->having);
		return $this;
	}
	
	/**
	 * where()
	*/
	public function where($data, $value=null, $override = 'AND') {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), $override);	
		$this->escapeListeners[] = end($this->where);
		return $this;
	}

	/**
	 * orWhere()
	*/	
	public function orWhere($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), 'OR');	
		$this->escapeListeners[] = end($this->where);
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

		$table = $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins = '';
		if (count($this->joins)) {
			foreach ($this->joins as $join) {
				$joins .= $join->render();
			}
		}
		
		$logicTypes = array('having' => $this->having, 'where' => $this->where);
		$logicRender = array('having' => null, 'where' => null);
		foreach ($logicTypes as $type => $stack) {
			if (count($stack)) {
				foreach ($stack as $condition) {
					list($expression, $logical) = $condition;
					$logical = ' '. strtoupper($logical) .' ';
					$result = count($stack) > 1 ? '('. $expression->render() .')' : $expression->render(); //dont need brackets if only 1 element
					$logicRender[$type][] = (count($logicRender[$type]) > 0 ? $logical : '') . $result; //dont add the first logical statement
				}
			}
		}
				
		$having = count($logicRender['having']) ? ' HAVING ' . implode(' ', $logicRender['having']) : '';
		$where =  count($logicRender['where'])  ? ' WHERE ' . implode(' ', $logicRender['where']) : '';
		$orderby = $this->orderby ? $this->orderby->render() : '';
		$groupby = $this->groupby ? $this->groupby->render() : '';
		
		return "SELECT $columns FROM $table$joins$having$where$orderby$groupby";
	}

	public function __toString() {
		return $this->render();
	}

}
