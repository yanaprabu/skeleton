<?php

require_once 'A/Sql/Statement.php';

class A_Sql_Delete extends A_Sql_Statement{
	protected $table = null;
	protected $where = null;
	protected $whereExpression;
	
	public function __construct($table=null, $where=array()) {
		$this->table($table);
		$this->where($where);
	}
	
	public function table($table) {
		if ($table) {
			if (!$this->table) include_once('A/Sql/Table.php');
			$this->table = new A_Sql_Table($table);
		}
		return $this;
	}

	public function where($data, $value=null, $override = 'AND') {
			include_once('A/Sql/Expression.php');
			$this->where[] = array(new A_Sql_Expression($data, $value), $override);	
			$this->escapeListeners[] = end($this->where);
			return $this;
		}
	}

	public function orWhere($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), 'OR');	
		$this->escapeListeners[] = end($this->where);
		return $this;		
	}	

	public function where($data, $value=null) {
		if ($data) {
			if (!$this->whereExpression) include_once ('A/Sql/Expression.php');
			if (!$this->where) include_once('A/Sql/List.php');
			$this->whereExpression = new A_Sql_Expression($data, $value);	
			$this->where = new A_Sql_List($this->whereExpression);
		}
		return $this;
	}
	
	public function having($data, $value=null, $override = 'AND') {
		include_once('A/Sql/Expression.php');
		$this->having[] = array(new A_Sql_Expression($data, $value), $override);	
		$this->escapeListeners[] = end($this->having);
		return $this;
	}

	public function orHaving($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->having[] = array(new A_Sql_Expression($data, $value), 'OR');	
		$this->escapeListeners[] = end($this->having);
		return $this;
	}

	function render() {
		if ($this->table) {
			$table = $this->table->render();
			$where = $this->where ? ' WHERE ' . $this->where->render() : '';
	
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

			return "DELETE FROM $table$where";
		}
	}

	public function __toString() {
		return $this->render();
	}

}
