<?php
include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Delete extends A_Db_Sql_Common {	protected $table = '';
	protected $where = array();
	
	public function __construct($db=null) {
		$this->db = $db;
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

	function toSQL($db=null) {
		if ($this->table) {		// must at least specify a table
			$this->setDB($db);			//override current database connection if passed
/*
			if (is_array($this->where)) {
				foreach ($this->where as $field => $value) {
					if ($this->db) {
						$value = $this->db->escape($value);
					} else {
						$value = $this->escape($value);
					}
					$tmp[] = $field . '=' . $this->quoteValue($value);
				}
				$where = implode(' AND ', $tmp);
			} else {
				$where = $this->where;
			}
			$this->sql = "DELETE FROM {$this->table} WHERE $where";
*/
			$this->sql = "DELETE FROM {$this->table} WHERE " . $this->equationList($this->where, '=', ' AND ');
		} else {
			$this->sql = '';
		}
		return $this->sql;
	}

	function execute($db=null) {
		$sql = $this->toSQL($db);
		if ($this->db && $sql) {
			return $this->db->query($sql);	
		}
	}

}
