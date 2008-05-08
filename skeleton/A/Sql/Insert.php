<?php

require_once 'A/Sql/Statement.php';

class A_Sql_Insert extends A_Sql_Statement {
	protected $table;
	protected $data = array();
	protected $columns = null;
	protected $select = null;
	

	/**
	 * table()
	*/	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	/**
	 * values()
	*/	
	public function values($data, $value=null) {
		if ($data) {
			// remove existing select
			$this->columns = null;
			$this->select = null;
			
			$this->data = array();
			if (is_array($data)) {
				$this->data = $data;	
			} elseif (is_string($data) && $value) {
				$this->data = array($data=>$value);	
			}
		}
		return $this;
	}
	
	public function columns() {
		include_once('A/Sql/Columns.php');
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}

	public function select() {
		if (! $this->select) {
			include_once('A/Sql/Select.php');
			$this->select = new A_Sql_Select();
		}
		return $this->select;
	}

	public function render($db=null) {
		$columns = array();
		if ($this->table) {
			$this->notifyListeners();
			if ($this->data) {
				$callback = $db ? array($db, 'escape') : 'addslashes';
				$columns = implode(', ', array_keys($this->data));
				$data = array_map($callback, array_values($this->data));
				$values = "VALUES ('" . implode("', '", $data) . "')";
			} elseif ($this->columns && $this->select) {
				$columns = $this->columns->render();
				$values = $this->select->render($db);
			}
		}
		if ($columns && $values) {
			return "INSERT INTO {$this->table} ($columns) $values";
		}
	}

	public function __toString() {
		return $this->render();
	}

}
