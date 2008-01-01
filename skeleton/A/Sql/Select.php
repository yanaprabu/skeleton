<?php

class A_Sql_Select {
	/**
	 * $table
	*/
	protected $table;

	/**
	 * $columns
	*/
	protected $columns;

	/**
	 * $where
	*/
	protected $where;

	/**
	 * $equation
	*/
	protected $equation;

	/**
	 * $whereLogic
	*/
	protected $whereLogic;
	
	/**
	 * $joins
	 * Unsupported
	*/
	protected $joins;

	/**
	 * $having
	 * Unsupported
	*/
	protected $having;

	/**
	 * $groupby
	 * Unsupported
	*/
	protected $groupby;			

	/** 
	 * $orderby
	 * Unsupported
	*/
	protected $orderby;
	
	/**
	 * columns()
	*/
	public function columns() {
		if (!$this->columns) include_once('A/Sql/Piece/Columns.php');
		$this->columns = new A_Sql_Piece_Columns(func_get_args());
		return $this;
	}

	/**
	 * from()
	*/
	public function from($table) {
		if (!$this->table) include_once('A/Sql/Piece/Table.php');
		$this->table = new A_Sql_Piece_Table($table);
		return $this;
	}

	/**
	 * where()
	*/
	public function where($data, $value=null) {
		if (!$this->where) include_once('A/Sql/Piece/Where.php');
		if (!$this->equation) include_once ('A/Sql/Piece/Equation.php');
		$this->equation = new A_Sql_Piece_Equation($data, $value);	
		$this->where = new A_Sql_Piece_Where($this->equation);
		return $this;
	}

	/**
	 * setWhereLogic()
	*/
	public function setWhereLogic($logic) {
		$this->whereLogic = $logic; 
	}

	/**
	 * join()
	 * Unsupported	 
	*/
	public function join($table1, $column1, $table2, $column2) {
		if (!$this->join) include_once('A/Sql/Piece/Join.php');
		$this->join = new A_Sql_Piece_Join($table1, $column1, $table2, $column2);
		return $this;
	}
	
	/**
	 * having()
	 * Unsupported	 
	*/	
	public function having() {
		if (!$this->having) include_once('A/Sql/Piece/Having.php');
		return $this;
	}
	
	/**
	 * groupby()
	 * Unsupported	 
	*/	
	public function groupby() {
		if (!$this->groupby) include_once('A/Sql/Piece/Groupby.php');
		return $this;
	}
	
	/**
	 * orderby()
	 * Unsupported
	*/	
	public function orderby() {
		if (!$this->orderby) include_once('A/Sql/Piece/Orderby.php');
		return $this;
	}

	/**
	 * render()
	*/
	public function render($db=null) {
		if (!$this->table) {
			return;
		}
		if ($this->where) {
			$this->where->setLogic($this->whereLogic);
			$this->equation->setEscapeCallback($db);
		}
		
		$table 	= $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins 	= $this->joins ? $this->joins->render() : '';
		$where 	= $this->where ? $this->where->render() : '1=1';
		/**
		 * TODO: This probably should be shifted to a more intelligent string
		 * parser to detect which components need to be supplimented
		*/
		return sprintf('SELECT %s FROM %s %s WHERE %s', $columns, $table, $joins, $where);
	}
}

?>