<?php
/**
 * Basic database connection functionality using the mysqli library
 * 
 * @package A_Db 
 */

class A_Db_Mysqli extends MySQLi {

	protected $dsn = null;
	protected $connected = false;
	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	
	public function __construct($dsn=null) {
		$this->dsn = $dsn;
	}
		
	public function connect ($dsn=null) {
		$result = false;
		if ($dsn) {
			$this->dsn = $dsn;
		}
		if (! $this->connected) {
			parent::connect($this->dsn['hostspec'], $this->dsn['username'], $this->dsn['password']);
			if (isset($this->dsn['database'])) {
				$result = $this->select_db($this->dsn['database'], $this->link);
			} else {
				$result = true;
			}
		}
		return $result;
	}
		
	public function disconnect() {
		if ($db->connected) {
			$this->close();
		} 
	}
		
	public function selectDb($database) {
		$this->dsn['database'] = $database;
		return $this->select_db($this->dsn['database']);
	}
		
	public function query($sql, $bind=array()) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		if ($bind) {
			#include_once 'A/Sql/Prepare.php';
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this->db);
			$sql = $prepare->render();
		}
		if (stripos($sql, 'select') === 0) {
			$obj = new A_Db_Mysqli_Recordset(parent::query($sql));
		} else {
			$obj = new A_Db_Mysqli_Result(parent::query($sql));
			$obj->affected_rows = $this->affected_rows;
		}
		$obj->errno = $this->errno;
		$obj->errmsg = $this->error;
		return $obj;
	}
		
	public function limit($sql, $count, $offset = null) {
		$limit = (is_int($offset) && $offset > 0) ? ($offset . ', ' . $count) : $count; 
		return $sql . ' LIMIT ' . $limit;
	}
		
	public function lastId() {
		return $this->insert_id();
	}
		
	public function nextId ($sequence) {
		if ($sequence) {
			$result = $this->query("UPDATE $sequence{$this->sequenceext} SET id=LAST_INSERT_ID(id+1)");
			if ($result) {
				$id = $this->insert_id();
				if ($id > 0) {
					return $id;
				} else {
					$result = $this->query("INSERT $sequence{$this->sequenceext} SET id=1");
					$id = $this->insert_id();
					if ($id > 0) {
						return $id;
					}
				}
			} elseif ($this->errno() == 1146) {		// table does not exist
				if ($this->createSequence($sequence)) {
					return $this->sequencestart;
				}
			}
		}
		return 0;
	}
		
	public function createSequence ($sequence) {
		$result = 0;
		if ($sequence) {
			$result = $this->query($this->link, "CREATE TABLE $sequence{$this->sequenceext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->sequencestart}");
		}
		return($result);
	}
		
	public function escape($value) {
		return $this->escape_string($this->link, $value);
	}
	
	public function isError() {
		return $this->errno;
	}
		
	public function getErrorMsg() {
		return $this->error;
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}


class A_Db_Mysqli_Result {
	protected $result;
	protected $affected_rows;
	public $errno;
	public $errmsg;
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function numRows() {
		if ($this->result) {
			return $this->affected_rows;
		} else {
			return 0;
		}
	}
		
	public function isError() {
		return $this->errno;
	}
		
	public function getErrorMsg() {
		return $this->errmsg;
	}

	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}




class A_Db_Mysqli_Recordset extends A_Db_Mysqli_Result {

public function __construct($result=null) {
	$this->result = $result;
}
	
public function fetchRow ($mode=null) {
	if ($this->result) {
		return $this->result->fetch_assoc($this->result);
	}
}
	
public function fetchObject ($class=null) {
	if ($this->result) {
		return $this->result->fetch_object($this->result, $class);
	}
}
	
public function fetchAll() {
	if ($this->result) {
		return $this->result->fetch_all($this->result);
	}
}
	
public function numRows() {
	if ($this->result) {
		return $this->result->num_rows;
	} else {
		return 0;
	}
}
	
public function numCols() {
	if ($this->result) {
		return $this->result->field_count;
	} else {
		return 0;
	}
}
	
public function __call($name, $args) {
	return call_user_func(array($this->result, $name), $args);
}
	
}
