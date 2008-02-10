<?php
include_once 'A/DataContainer.php';

class A_Db_Activerecord extends A_DataContainer {
	protected static $globaldb = null;
	protected $db = null;
	protected $table;
	protected $key = 'id';
	protected $num_rows = 0;
	protected $is_loaded = false;

#	Do we need a A_Db_Activerecord_List class that contains an array of A_Db_Activerecord objects?
#	it would a separate array to iterate over

#	protected $has_many = array of A_Db_Activerecord_Hasmany (or A_Db_Activerecord) objects that
#	knows the table.field=table.field mapping and is a list of records

	public function __construct($db=null, $table=null) {
		if ($db) {
			$this->db = $db;
		} elseif (self::$globaldb) {
			$this->db = self::$globaldb;
		}
		$this->setTable($table);
	}
	
	public function setDb($db) {
		if (isset($this)) {
			$this->db = $db;
			return $this;
		} else {
			self::$globaldb = $db;
		}
	}
	
	public function setTable($table=null) {
		if ($table) {
			$this->table = $table;
		} else {
			$this->table = strtolower(get_class($this));
		}
	}
	
	public function getTable() {
		return $this->table;
	}
	
	public function find($sql) {
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $sql");
		if (! $this->db->isError()) {
			$numrows = $result->numRows();
			if ($numrows > 0) {
				$this->_data = array();
				if ($numrows == 1) {
					$this->_data[0] = $result->fetchRow();
					$this->num_rows = 1;
				} else {
					$this->num_rows = 0;
					while ($this->_data[$this->num_rows] = $result->fetchRow()) {
						++$this->num_rows;
					}
					// last fetch returns null, unset so to avoid copying rows in loop
					unset($this->_data[$this->num_rows]);
				}
				$this->is_loaded = true;
			} else {
				// error
			}
		} else {
			//error
		}
	}

	public function findBy($field, $value) {
	}
	
	public function findOrCreateBy($field, $value) {
	}
	
	public function save() {
		if ($this->is_loaded) {
			// update
		} else {
			// insert
		}
	}
	
	public function delete() {
		if ($this->is_loaded) {
			// update
		}
	}

}