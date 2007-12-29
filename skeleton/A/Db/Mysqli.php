<?php

class A_Db_Mysqli {	protected $dsn = null;	protected $link = null;	protected $sequenceext = '_seq';	protected $sequencestart = 1;
	
	public function __construct($dsn=null) {
		if ($dsn) {
			$this->connect($dsn);
		}
	}
		
	public function connect ($dsn=null) {
		$result = false;
		if ($dsn) {
			$this->dsn = $dsn;
		}
		if ($this->link == null) {
			$this->link = mysqli_connect($this->dsn['hostspec'], $this->dsn['username'], $this->dsn['password']);
			if ($this->link) {
				if (isset($this->dsn['database'])) {
					$result = mysqli_select_db($this->dsn['database'], $this->link);
				} else {
					$result = true;
				}
			}
		}
		return $result;
	}
		
	public function disconnect() {
		if ($db->link) {
			mysqli_disconnect($db->link);
		} 
	}
		
	public function query ($sql) {
		if (is_object($sql) && method_exists($sql, 'execute')) {
			// convert object to string by executing SQL builder object
			$sql = $sql->execute($this);   // pass $this to provide db specific escape() method
		}
		mysql_select_db($this->dsn['database'], $this->link);
		if (strpos(strtolower($sql), 'select') === 0) {
			$obj = new A_Db_Mysqli_Recordset(mysqli_query($sql));
		} else {
			$obj = new A_Db_Mysqli_Result(mysqli_query($sql));
		}
		$obj->errno = mysqli_errno($this->link);
		$obj->errmsg = mysqli_error($this->link);
		return $obj;
	}
		
	public function limitQuery ($sql, $from, $count) {
		return($this->query($sql . " LIMIT $from,$count"));
	}
		
	public function lastId() {
		if ($this->link) {
			return(mysqli_insert_id($this->link));
		} else {
			return 0;
		}
	}
		
	public function nextId ($sequence) {
	    if ($sequence) {
		    $result = mysqli_query("UPDATE $sequence{$this->sequenceext} SET id=LAST_INSERT_ID(id+1)");
	    	if ($result) {
		        $id = mysqli_insert_id($this->link);
		        if ($id > 0) {
		            return $id;
		        } else {
				    $result = mysqli_query("INSERT $sequence{$this->sequenceext} SET id=1");
			        $id = mysqli_insert_id($this->link);
			        if ($id > 0) {
			            return $id;
			        }
		        }
			} elseif (mysqli_errno() == 1146) {		// table does not exist
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
		    $result = mysqli_query($this->link, "CREATE TABLE $sequence{$this->sequenceext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->sequencestart}");
	    }
	    return($result);
	}
		
	public function escape($value) {
		return mysqli_real_escape_string($this->link, $value);
	}
	
	public function isError() {
		return mysqli_errno($this->link);
	}
		
	public function getMessage() {
		return mysqli_error($this->link);
	}
	
} // end DAO class


class A_Db_Mysqli_Result {	protected $result;	public $errno;	public $errmsg;
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function numRows() {
		if ($this->result) {
			return mysql_affected_rows($this->result);
		} else {
			return 0;
		}
	}
		
	public function isError() {
		return $this->errno;
	}
		
	public function getMessage() {
		return $this->errmsg;
	}

}




class A_Db_Mysqli_Recordset extends A_Db_Mysqli_Result {

public function __construct($result=null) {
	$this->result = $result;
}
	
public function fetchRow ($mode=null) {
	if ($this->result) {
		return mysqli_fetch_assoc($this->result);
	}
}
	
public function fetchObject ($mode=null) {
	if ($this->result) {
		return mysqli_fetch_object($this->result);
	}
}
	
public function numRows() {
	if ($this->result) {
		return mysqli_num_rows($this->result);
	} else {
		return 0;
	}
}
	
public function numCols() {
	if ($this->result) {
		return(mysqli_num_cols($this->result));
	} else {
		return 0;
	}
}
	
}
