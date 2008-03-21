<?php

class A_Sql_Update {
	protected $sqlFormat = 'UPDATE %s SET %s%s';
	protected $table;
	protected $set;
	protected $setExpression;
	protected $where;
	protected $whereExpression;
	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function set($data, $value=null) {
		if (!$this->setExpression) include_once ('A/Sql/Expression.php');
		if (!$this->set) include_once('A/Sql/List.php');
		$this->setExpression = new A_Sql_Expression($data, $value);	
		$this->set = new A_Sql_List($this->setExpression);
		return $this;
	}
	
	public function where($data, $value=null) {
		if (!$this->whereExpression) include_once ('A/Sql/Expression.php');
		if (!$this->where) include_once('A/Sql/List.php');
		$this->whereExpression = new A_Sql_Expression($data, $value);	
		$this->where = new A_Sql_List($this->whereExpression);
		return $this;
	}

	public function render($db=null) {
		if ($this->table) {
			if ($this->where) {
				$this->whereExpression->setEscapeCallback($db);
			}
						
			if ($this->set) {
				$this->setExpression->setEscapeCallback($db);
			}
			
			$table = $this->table->render();
			$set = $this->set->render();
			$where = $this->where ? ' WHERE ' . $this->where->render() : '';

			return sprintf($this->sqlFormat, $table, $set, $where);
		}
	}

	public function __toString() {
		return $this->render();
	}

}
