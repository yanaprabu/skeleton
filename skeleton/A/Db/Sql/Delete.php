<?php
include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Delete extends A_Db_Sql_Common {	protected $table = '';
	protected $where = array();
	protected $sql = '';
	protected $db;
	
	public function __construct() {
		$this->db = $this;
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
		if ($this->table && $this->where) {
			if (is_array($this->where)) {
				foreach ($this->where as $field => $value) {
					$tmp[] = $field . '=' . $this->quoteValue($this->db->escape($value));
				}
				$where = implode(' AND ', $tmp);
			} else {
				$where = $this->where;
			}
			$this->sql = "DELETE FROM {$this->table} WHERE $where";
		} else {
			$this->sql = '';
		}
		return $this->sql;
	}

}
