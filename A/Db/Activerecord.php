<?php
include_once 'A/DataContainer.php';
include_once 'A/Sql/Select.php';

class A_Db_Activerecord extends A_DataContainer {
	protected static $globaldb = null;
	protected $db = null;
	protected $table;
	protected $key = 'id';
	protected $select;
	protected $errmsg = '';
	protected $columns = '*';
	public $sql = '';
	protected $num_rows = 0;
	protected $is_loaded = false;

#	Do we need a A_Db_Activerecord_List class that contains an array of A_Db_Activerecord objects?
#	it would a separate array to iterate over

#	protected $has_many = array of A_Db_Activerecord_Hasmany (or A_Db_Activerecord) objects that
#	knows the table.field=table.field mapping and is a list of records

	public function __construct($db=null, $table='', $key='id') {
		if ($db) {
			$this->db = $db;
		} elseif (self::$globaldb) {
			$this->db = self::$globaldb;
		}
		$this->table($table);
		$this->key = $key;
		$this->select = new A_Sql_Select();
		$this->select->from($this->getTable());
	}

	public function setDb($db) {
		if (isset($this)) {
			$this->db = $db;
			return $this;
		} else {
			self::$globaldb = $db;
		}
	}
	
	public function table($table=null) {
		if ($table) {
			$this->table = $table;
		} else {
			$this->table = strtolower(get_class($this));
		}
		return $this;
	}
	
	public function getTable() {
		return $this->table;
	}
	
	public function setColumns($columns) {
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

		$this->sql = $this->select->render();
		$result = $this->db->query($this->sql);
		if ($result->isError()) {
			$this->errmsg = $result->getMessage();
			$this->is_loaded = false;
		} else {
			$this->_data = $result->fetchRow();
			$this->num_rows = count($this->_data);
			$this->is_loaded = true;
		}
		return $this->errmsg;
	}
	
	public function save() {
		if (! $this->is_loaded) {
			include_once 'A/Sql/Insert.php';
			$insert = new A_Sql_Insert();
			$insert->table($this->table)->values($this->_data);
			$this->sql = $insert->render();
			$this->db->query($this->sql);
			$try_update = ! $this->db->isError();
		}
		if (isset($this->_data[$this->key]) && ($this->is_loaded || $try_update)) {
			include_once 'A/Sql/Update.php';
			$update = new A_Sql_Update();
			$update->table($this->table)->set($this->_data)->where($this->key, $this->_data[$this->key]);
			$this->sql = $update->render();
			$this->db->query($this->sql);
		}
	}
	
	public function delete() {
		if (isset($this->_data[$this->key]) && $this->is_loaded) {
			include_once 'A/Sql/Delete.php';
			$delete = new A_Sql_Delete();
			$delete->table($this->table)->where($this->key, $this->_data[$this->key]);
			$this->sql = $delete->render();
			$this->db->query($this->sql);
		}
	}

}