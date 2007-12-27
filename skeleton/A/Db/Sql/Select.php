<?php
include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Select extends A_Db_Sql_Common {	protected $columns = array();	protected $where = array();
	protected $tables = null;

	public function __construct() {
		$this->db = $this;
	}
		
	function columns($columns) {
		if (is_array($columns)) {
			$this->columns = array_merge($this->columns, $columns);
		} else {
			$this->columns = $columns;
		}
		return $this;
	}

	function from($table) {
		$this->table = $table;
		return $this;
	}

	function table($table) {
		$this->table = $table;
		return $this;
	}

	function where($data, $value=null) {
		if (is_array($data)) {
			$this->where = array_merge($this->where, $data);
		} elseif ($value !== null) {
			$this->where[$data] = $value;
		} else {
			$this->where = $value;
		}
		return $this;
	}

	function execute($db=null) {
		$this->sql = '';
		if ($this->table && $this->columns) {
			if ($db !== null) {
				$this->db = $db;
			}
			if ($this->columns && is_array($this->columns)) {
				$this->columns = implode(',', $this->columns);
			}
			if (is_array($this->where)) {
				// if data in array then build expressions
				$tmp = array();
				foreach ($this->where as $field => $value) {
					$tmp[] = $field . '=' . $this->quoteValue($this->db->escape($value));
				}
				$this->where = implode(' AND ', $tmp);		// need to support more than AND
			}
			$this->sql = "SELECT {$this->columns} FROM {$this->table} WHERE ({$this->where})";
		}
		return $this->sql;
	}

}
