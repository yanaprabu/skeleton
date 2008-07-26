<?php

/**
 * A_Db_Schema
 *
 * - Requires A_Db_Schema
 * @package A_Db
 * @license    BSD
 * @version    $Id:$
 */
class A_Db_Schema {
	protected $db = null;
	protected $table;
	protected $fields = array();
	protected $key = '';
	
	/*
	 * object is created/initailized by connection class and returned by $db->schema() 
	 */
	public function __construct($db, $table, $fields) {
		$this->db = $db;
		$this->table = $table;
		$this->fields = $fields;
	}

	public function getTable() {
		return $this->table;
	}
	
	public function setTable($table) {
		$this->table = $table;
		return $this;
	}
	
	public function getField() {
		return isset($this->field[$name]) ? $this->field[$name] : null;
	}
	
	public function setField($name, $type, $size=0, $default=null, $isKey=false, $other=array()) {
		$this->fields[$name]['Name'] = $name;
		$this->fields[$name]['Type'] = $type;
		$this->fields[$name]['Size'] = $size;
		$this->fields[$name]['Default'] = $default;
		$this->fields[$name]['Key'] = $isKey;
		$this->fields[$name]['Other'] = $other;
		return $this;
	}
	
	public function getFields() {
		return array_keys($this->fields);
	}
	
	public function getPrimaryKey() {
		if (! $this->key) {
			foreach ($this->fields as $field => $data) {
				if ($data['Key'] == 'PRI') {
					$this->key = $field;
				}
			}
		}
		return $this->key;
	}
	
	public function isPrimaryKey($name) {
		return $this->getPrimaryKey() == $name;
	}
	
	public function getType($field) {
		return isset($this->fields[$field]) ? $this->fields[$field]['Type'] : null;
	}
	
	public function getSize($field) {
		if (isset($this->fields[$field])) {
			if (! isset($this->fields[$field]['Size'])) {
				preg_match('/\([0-9\.]*\)/', $this->fields[$field]['Type'], $matches);
				$this->fields[$field]['Size'] = isset($matches[0]) ? trim($matches[0], '()') : 0;
			}
			return $this->fields[$field]['Size'];
		}
	}
	
	public function getDefault($field) {
		return isset($this->fields[$field]) ? $this->fields[$field]['Default'] : null;
	}
	
}