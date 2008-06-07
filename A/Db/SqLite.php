<?php
/*
 * DSN array contain:
 * 'filename'
 * 'mode'
 */

class A_Db_Sqlite {	protected $dsn = null;	protected $link = null;	protected $sequenceext = '_seq';	protected $sequencestart = 1;
	
	public function __construct($dsn=null) {
		if ($dsn) {
			$this->connect($dsn);
		}
	}
		
	public function connect ($dsn=null, $options=null) {
		if ($this->link == null) {
			$this->link = sqlite_open($dsn['filename'], $dsn['mode']);
		} 
	}
		
	public function disconnect() {
		if ($db->link) {
			sqlite_close($db->link);
		} 
	}
		
	public function query ($sql) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}

		if (strpos(strtolower($sql), 'select') === 0) {
			$obj = new A_Db_Sqlite_Recordset(sqlite_query($this->link, $sql));
		} else {
			$obj = new A_Db_Sqlite_Result($this->link, sqlite_query($this->link, $sql));
		}
		$obj->errno = sqlite_last_error($this->link);
		$obj->errmsg = sqlite_error_string($obj->errno);
		return $obj;
	}
		
	public function limitQuery ($sql, $from, $count) {
		return($this->query($sql . " LIMIT $from,$count"));
	}
		
	public function lastId() {
		if ($this->link) {
			return(sqlite_last_insert_rowid($this->link));
		} else {
			return 0;
		}
	}
		
	public function nextId ($sequence) {
	    return 0;
	}
		
	public function createSequence ($sequence) {
	    return 0;
	}
		
	public function escape($value) {
		return sqlite_escape_string($value);
	}
	
	public function isError() {
		return sqlite_last_error($this->link);
	}
		
	public function getMessage() {
		return sqlite_error_string(sqlite_last_error($this->link));
	}
	
}


class A_Db_Sqlite_Result {	protected $result;	public $errno;	public $errmsg;
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function isError() {
		return $this->errno;
	}
		
	public function getMessage() {
		return $this->errmsg;
	}
	
}




class A_Db_Sqlite_Recordset extends A_Db_Sqlite_Result {

	public function __construct($result=null) {
		$this->result = $result;
	}
	
	public function fetchRow () {
		if ($this->result) {
			return(sqlite_fetch_array($this->result, SQLITE_ASSOC));
		}
	}
		
	public function fetchObject () {
		if ($this->result) {
			return(sqlite_fetch_object($this->result));
		}
	}
		
	public function numRows() {
		if ($this->result) {
			return(sqlite_num_rows($this->result));
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return(sqlite_num_cols($this->result));
		} else {
			return 0;
		}
	}
	
}
