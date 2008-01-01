<?php

class A_Db_Sql_Select {
	protected $sqlFormat = 'SELECT %s FROM %s %s WHERE %s';
	protected $sql = '';
	protected $table;
	protected $columns;
	protected $where;
	protected $joins;

	public function table($table) {
		if (!$this->table) include_once('A/Db/Sql/Piece/Table.php');
		$this->table = new A_Db_Sql_Piece_Table($table);
		return $this;
	}
	
	public function set($data, $value=null) {
		$this->equation = new A_Db_Piece_Equation($data, $value);
		return $this;
	}

	public function where($data, $value=null) {
		if (!$this->where) include_once('A/Db/Sql/Piece/Where.php');	
		$this->where = new A_Db_Sql_Piece_Where($data, $value);
		return $this;
	}

	public function setWhereLogic($logic) {
		if ($this->where instanceof A_Db_Sql_Piece_Where) {
			$this->where->setLogic($logic);
		}
		$this->whereLogic = $logic; 
	}

	public function toSQL($db=null) {
		if ($this->table) {
			if ($this->where) {
				$this->where->setLogic($this->wherelogic);
				$this->where->setEscapeCallback($db);
			}
			
			if ($this->equation) {
				$this->equation->setEscapeCallback($db);
			}
			
			$table 	 = $this->table->parse();
			$equation = $this->equation->parse();
			$where 	 = $this->where ? $this->where->parse() : '1=1';

			$this->sql = sprintf($this->sqlFormat, $columns, $table, $joins, $where);
		}
		
		return $this->sql;
	}
}

$select = new A_Db_Sql_Select;
echo $select->columns()->from('foobar')->toSQL();


?>