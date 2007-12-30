<?php
include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Insert extends A_Db_Sql_Common {	protected $table = '';
	protected $fields = array();
	protected $values = array();
	
	public function __construct() {
		$this->db = $this;
	}
		
	function table($table) {
		$this->table = $table;
		return $this;
	}

	function fields($fields) {
		if (is_array($fields)) {
			$this->fields = array_merge($this->fields, $fields);
		} else {
			$this->fields = $fields;
		}
		return $this;
	}

	function values($values) {
		if (is_array($values)) {
			$this->values = array_merge($this->values, $values);
		} else {
			$this->values = $fields;
		}
		return $this;
	}

	function toSQL($db=null) {
		if ($this->table && $this->values) {	// must at least specify a table and values to insert
			$this->setDB($db);			//override current database connection if passed
			$fields = '';
			if ($this->fields && is_array($this->fields)) {
				$this->fields = implode(',', $this->fields);
			}
			if (is_array($this->values)) {
				$fields = array();
				$values = array();
				foreach ($this->values as $field => $value) {
					$fields[] = $field;
					$values[] = $this->quoteValue($this->db->escape($value));
				}
				if (! $this->fields) {
					$this->fields = implode(',', $fields);
				}
				$this->values = implode(',', $values);
			}
			$this->sql = "INSERT INTO {$this->table} ({$this->fields}) VALUES ({$this->values})";
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
