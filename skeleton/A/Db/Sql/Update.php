<?php
include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Update extends A_Db_Sql_Common {	protected $table = '';
	protected $data = array();
	protected $where = array();
	protected $db;
	
	public function __construct() {
		$this->db = $this;
	}
		
	function table($table) {
		$this->table = $table;
		return $this;
	}

	function set($data, $value=null) {
		if (is_array($data)) {
			$this->data = array_merge($this->data, $data);
		} elseif ($value !== null) {
			$this->data[$data] = $value;
		} else {
			$this->data = $value;
		}
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
		if ($this->table && $this->data && $this->where) {
			if ($db !== null) {
				$this->db = $db;
			}
			if (is_array($this->data)) {
				// if data in array then build comma separated assignments
				$tmp = array();
				foreach ($this->data as $field => $value) {
					$tmp[] = $field . '=' . $this->quoteValue($this->db->escape($value));
				}
				$set = implode(',', $tmp);
			} else {
				$set = $this->data;
			}
			if (is_array($this->where)) {
				// if data in array then build expressions
				$tmp = array();
				foreach ($this->where as $field => $value) {
					$tmp[] = $field . '=' . $this->quoteValue($this->db->escape($value));
				}
				$where = implode(' AND ', $tmp);		// need to support more than AND
			} else {
				$where = $this->where;
			}
			$this->sql = "UPDATE {$this->table} SET $set WHERE $where";
		}
		return $this->sql;
	}

}
