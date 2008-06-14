<?php
/*
 * DSN array contain:
 * 'host' or 'hostspec'
 * 'user' or 'username'
 * 'password'
 * 'dbname' or 'database'
 * 'port'
 *  */

class A_Db_Postgres {
	protected $link = null;
	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	
	public function __construct($dsn=null) {
		if ($dsn) {
			$this->connect($dsn);
		}
	}
		
	public function connect ($dsn, $options=null) {
		$connstr = '';
		foreach ($dsn as $param => $value) {
			if ($value) {
				switch ($param) {
				case 'hostspec':
				case 'host':
					$connstr .= "host={$value} ";
					break;
				case 'dbname':
				case 'database':
					$connstr .= "dbname={$value} ";
					break;
				case 'port':
					$connstr .= "port={$value} ";
					break;
				case 'username':
				case 'user':
					$connstr .= "user={$value} ";
					break;
				case 'password':
					$connstr .= "password={$value} ";
					break;
				}
			}
		}
		if ($this->link == null) {
			$this->link = @pg_pconnect($connstr);
		}
		return $this->link;
	}
		
	public function disconnect () {
		if ($this->link) {
			pg_disconnect($this->link);
		} 
	}
		
	public function query ($sql) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		mysql_select_db($this->dsn['database'], $this->link);
		if (strpos(strtolower($sql), 'select') === 0) {
			$obj = new A_Db_Postgres_Recordset(pg_query($sql));
		} else {
			$obj = new A_Db_Postgres_Result(pg_query($sql));
		}
		$obj->errmsg = pg_last_error($this->link);
		$obj->errno = $obj->errmsg != '';
		return $obj;
	}
		
	public function limit($sql, $count, $offset = null) {
		$limit = (is_int($offset) && $offset > 0) ? ($offset . ', ' . $count) : $count; 
		return $sql . ' LIMIT ' . $limit;
	}
		
	public function nextId ($sequence) {
	    if ($sequence) {
		    $result = pg_query($this->link, "SELECT nextval('$sequence')");
	    	if ($result) {
		        $row = pg_fetch_array($this->link);
				return $row[0];
			} else {		// table does not exist
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
		    $result = pg_query($this->link, "CREATE SEQUENCE $sequence START {$this->sequencestart}");
	    }
	    return($result);
	}
		
	public function escape($value) {
		return pg_escape_string($value);
	}
	
	public function isError () {
		return pg_last_error($this->link) != '';
	}
		
	public function getMessage () {
		return pg_last_error($this->link);
	}
	
}


class A_Db_Postgres_Result {
	protected $result;
	public $errno;
	public $errmsg;
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function numRows() {
		if ($this->result) {
			return(mysql_affected_rows($this->result));
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


class A_Db_Postgres_Recordset extends A_Db_Postgres_Result {

	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function fetchRow ($mode=null) {
		if ($this->result) {
			return pg_fetch_assoc($this->result);
		}
	}
		
	public function fetchObject () {
		if ($this->result) {
			return pg_fetch_object($this->result);
		}
	}
		
	public function numRows () {
		if ($this->result) {
			return pg_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	public function numCols () {
		if ($this->result) {
			return pg_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}
