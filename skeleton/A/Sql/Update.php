<?php

class A_Sql_Update {
	protected $sqlFormat = 'UPDATE %s SET %s%s';
	protected $table;
	protected $set;
	protected $setEquation;
	protected $where;
	protected $whereEquation;
	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function set($data, $value=null) {
		if (!$this->setEquation) include_once ('A/Sql/Equation.php');
		if (!$this->set) include_once('A/Sql/List.php');
		$this->setEquation = new A_Sql_Equation($data, $value);	
		$this->set = new A_Sql_List($this->setEquation);
		return $this;
	}
	
	public function where($data, $value=null) {
		if (!$this->whereEquation) include_once ('A/Sql/Equation.php');
		if (!$this->where) include_once('A/Sql/List.php');
		$this->whereEquation = new A_Sql_Equation($data, $value);	
		$this->where = new A_Sql_List($this->whereEquation);
		return $this;
	}

	public function render($db=null) {
		if ($this->table) {
			if ($this->where) {
				$this->whereEquation->setEscapeCallback($db);
			}
						
			if ($this->set) {
				$this->setEquation->setEscapeCallback($db);
			}
			
			$table = $this->table->render();
			$set = $this->set->render();
			$where = $this->where ? ' WHERE ' . $this->where->render() : '';

			return sprintf($this->sqlFormat, $table, $set, $where);
		}
	}
}
