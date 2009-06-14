<?php
include_once 'A/Sql/Select.php';
/**
 * Datasource access using the Table Data Gateway pattern
 *
 * @package A_Db
 */
class A_Db_Tabledatagateway {
	protected $db;
	protected $table = '';
	protected $key = 'id';
	protected $columns = '*';
	protected $errmsg = '';
	public $sql = '';
	protected $num_rows = 0;
	
	public function __construct($db, $table=null, $key=null) {
		$this->db = $db;
		$this->table($table);
		$this->key($key);
		$this->select = new A_Sql_Select();
		$this->select->from($this->getTable());
	}

	public function table($table=null) {
		if ($table) {
			$this->table = $table;
		} elseif ($this->table == '') {
			$this->table = strtolower(get_class($this));
		}
		return $this;
	}
	
	public function getTable() {
		return $this->table;
	}
	
	public function key($key='') {
		$this->key = $key ? $key : 'id';
		return $this;
	}
	
	public function getKey() {
		return $this->key;
	}
	
	public function columns($columns) {
		$this->columns = $columns;
		return $this;
	}
	
	public function where() {
		$args = func_get_args();
		// allow one param that is array of args
		if (is_array($args[0])) {
			$args = $args[0];
		}
		$nargs = count($args);
#print_r($args);
#echo "nargs=$nargs</br>";
		if ($nargs == 1) {
			// find match for key
			$this->select->where($this->key . '=', $args[0]);
		} else {
			$this->select->where($args[0], $args[1], isset($args[2]) ? $args[2] : null);
		}
		return $this;
	}

	public function find() {
		$allrows = array();

		$args = func_get_args();
		// if params then where condition passed
		if (count($args)) {
			$this->where($args);
		}

		$this->sql = $this->select
						->columns($this->columns)
						->from($this->getTable())
						->render();
		$result = $this->db->query($this->sql);
		if (! $result->isError()) {
			while ($row = $result->fetchRow()) {
				$allrows[] = $row;
			}
			$this->num_rows = count($allrows);
		}
		return $allrows;
	}
	
	public function update($id, $data) {
		if ($id && $data) {
			if (isset($data[$this->key])) {
				unset($data[$this->key]);
			}
			foreach ($data as $field => $value) {
				$sets[] = $field . "='" . $this->db->escape($value) . "'";
			}
			$this->sql = "UPDATE {$this->table} SET " . implode(',', $sets) . " WHERE {$this->key}='$id'";
			$this->db->query($this->sql);
		}
	}
	
	public function insert($data) {
		if ($data) {
			// if one row then 1st element is scalar
			if(! is_array(current($data))) {
				$cols = array_keys($data);
				$data = array($data);
			} else {
				$cols = array_keys(current($data));
			}
			$values = array();
			foreach ($data as $row) {
				if (empty($row[$this->key])) {			// remove array element for key unless it contains a value
					unset($row[$this->key]);
					unset($cols[$this->key]);
				}
				foreach ($row as $key => $value) {
					$row[$key] = $this->db->escape($value);
				}
				$values[] = "('" . implode("','", $row) . "')";
			}
			$this->sql = "INSERT INTO {$this->table} (" . implode(',', $cols) . ') VALUES ' . implode(',', $values);
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
		return $this->db->isError() || ($this->errmsg != '');
	}
	
	public function getErrorMsg() {
		return $this->db->getMessage() . $this->errmsg;
	}
	
	
}
