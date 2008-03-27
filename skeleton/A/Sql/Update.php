<?php

class A_Sql_Update {
	protected $sqlFormat = 'UPDATE %s SET %s%s';
	protected $table;
	protected $data;
	protected $where;
	protected $whereExpression;
	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function set($data, $value=null) {
		if ($data) {
			$this->data = array();
			if (is_array($data)) {
				$this->data = $data;	
			} elseif (is_string($data) && $value) {
				$this->data = array($data=>$value);	
			}
		}
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
						
			if ($this->data) {
				$callback = $db ? array($db, 'escape') : 'addslashes';
				$columns = array_keys($this->data);
				$data = array_map($callback, array_values($this->data));
				foreach ($columns as $key => $column) {
					$sets[] = "$column='{$data[$key]}'";
				}

				$table = $this->table->render();
				$set = implode(', ', $sets);
				$where = $this->where ? ' WHERE ' . $this->where->render() : '';

				return sprintf($this->sqlFormat, $table, $set, $where);
			}
		}
	}

	public function __toString() {
		return $this->render();
	}

}
