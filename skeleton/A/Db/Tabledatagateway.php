<?php

class A_Db_Tabledatagateway {
	protected $db;
	protected $table = '';
	protected $key = 'id';
	protected $fields = '*';
	protected $errmsg = '';
	protected $where = '';
	protected $orderby = '';
	protected $limit = '';
	public $sql = '';
	protected $num_rows = 0;
	
	public function __construct($db, $table, $key='id') {
		$this->db = $db;
		$this->table = $table;
		$this->key = $key;
	}

	public function quoteEscape($value) {
		return "'" . $this->db->escape($value) . "'";
	}

	public function findByKey($id) {
		$id = $this->db->escape($id);
		$this->where = '';
		$this->isEqual($this->key, $id);
		$allrows = $this->find();
		if (isset($allrows[0])) {
			return $allrows[0];
		}
	}

	public function findWhere($where='', $sort='') {
		if ($where) {
			$where = ' WHERE ' . $where;
		}
		if ($sort) {
			$sort = ' ORDER BY ' . $sort;
		}
		$this->sql = "SELECT {$this->fields} FROM {$this->table}$where$sort";
		return $this->db->query($this->sql);
	}

	public function find() {
		$allrows = array();
		$where = ($this->where ? ' WHERE ' . implode(' AND ', $this->where) : '');
		$orderby = ($this->orderby ? ' ORDER BY ' . implode(', ', $this->orderby) : '');
		$this->sql = "SELECT {$this->fields} FROM {$this->table}$where$orderby{$this->limit}";
		$result = $this->db->query($this->sql);
		if ($result->isError()) {
			$this->errmsg = $result->getMessage();
		} else {
			while ($row = $result->fetchRow()) {
				$allrows[] = $row;
			}
			$this->num_rows = count($allrows);
		}
		$this->where = '';
		return $allrows;
	}
	
	public function where($where) {
		$this->where[] = $where;
	}
	
	public function is($field, $op, $value, $quote=true) {
		$this->where[] = "$field$op" . ($quote ? $this->quoteEscape($value) : $this->db->escape($value));
	}

	public function isEqual($field, $value) {
		$this->is($field, '=', $value);
	}

	public function isNotEqual($field, $value) {
		$this->is($field, '!=', $value);
	}

	public function isGreaterThan($field, $value) {
		$this->is($field, '>', $value);
	}

	public function isLessThan($field, $value) {
		$this->is($field, '<', $value);
	}

	public function isGreaterThanOrEqual($field, $value) {
		$this->is($field, '>=', $value);
	}

	public function isLessThanOrEqual($field, $value) {
		$this->is($field, '<=', $value);
	}

	public function isLike($field, $value) {
		$this->is($field, ' LIKE ', "%$value%");
	}

	public function isNotLike($field, $value) {
		$this->is($field, ' NOT LIKE ', "%$value%");
	}

	public function isBetween($field, $value1, $value2) {
		$this->where[] = "$field BETWEEN " . $this->quoteEscape($value1) . ' AND ' . $this->quoteEscape($value2);
	}

	public function orderBy($orderby) {
		$this->orderby[] = $orderby;
	}

	public function setFields($fields) {
		if (is_array($fields)) {
			$this->fields = implode(',', $fields);
		} else {
			$this->fields = $fields;
		}
		return $this;
	}

	public function sortTable($table) {
		$this->table = $table;
		return $this;
	}

	public function sortKey($key) {
		$this->key = $key;
		return $this;
	}

	public function limit($start, $size) {
		$this->limit = " LIMIT $start, $size";
		return $this;
	}

	public function update($id, $data) {
		if ($id && $data) {
			if (isset($data[$this->key])) {
				unset($data[$this->key]);
			}
			foreach ($data as $field => $value) {
				$sets[] = $field . '=' . $this->quoteEscape($value);
			}
			$this->sql = "UPDATE {$this->table} SET " . implode(',', $sets) . " WHERE {$this->key}='$id'";
			$this->db->query($this->sql);
		}
	}
	
	public function insert($data) {
		if ($data) {
			if (empty($data[$this->key])) {			// remove array element for key unless it contains a value
				unset($data[$this->key]);
			}
			foreach ($data as $field => $value) {
				$cols[] = $field;
				$values[] = $this->quoteEscape($value);
			}
			$this->sql = "INSERT INTO {$this->table} (" . implode(',', $cols) . ') VALUES (' . implode(',', $values) . ')';
			$this->db->query($this->sql);
			return $this->db->lastId();
		}
	}
	
	public function delete($id) {
		if ($id) {
			$this->sql = "DELETE FROM {$this->table} WHERE {$this->key}='$id'";
			$this->db->query($this->sql);
		}
	}
	
	public function numRows() {
		return $this->num_rows;
	}
	
	public function isError() {
		return $this->db->isError();
	}
	
	public function getMessage() {
		return $this->db->getMessage();
	}
	
	
}

