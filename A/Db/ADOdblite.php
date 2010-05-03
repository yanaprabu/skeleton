<?php
/**
 * Database connection class using ADOdb
 *
 * @package A_Db
 * 
 * DSN array contain:
 * 'type'
 * 'hostspec'
 * 'username'
 * 'password'
 * 'database'
 */

class A_Db_ADOdblite {
	protected $dsn = null;
	protected $adodb = null;
	protected $limit = '';
	protected $orderby = '';
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
		if ($this->adodb == null) {
			$this->adodb = NewADOConnection($this->dsn['type'] . '://' . $this->dsn['username'] . ':' . $this->dsn['password'] . '@' . $this->dsn['hostspec'] . '/' . $this->dsn['database']);
		}
		return $result;
	}
		
	public function close() {
		if ($this->adodb) {
			$this->adodb->Close();
		} 
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
		if (strpos(strtolower($sql), 'select') === 0) {
			$obj = new A_Db_ADOdblite_Recordset($this->query($sql));
		} else {
			$obj = new A_Db_ADOdblite_Result($this->query($sql));
		}
		$obj->error = $this->adodb->ErrorNo();
		$obj->errmsg = $this->adodb->ErrorMsg();
		return $obj;
	}
		
	public function limitQuery ($sql, $from, $count) {
		return($this->query($sql . " LIMIT $from,$count"));
	}
	
	public function lastId() {
		if ($this->adodb) {
			return $this->adodb->Insert_ID();
		} else {
			return 0;
		}
	}
		
	public function nextId ($sequence=null, $startID=null) {
		if ($sequence) {
			$result = $this->adodb->GenID($seqName, $startID);
		}
		return 0;
	}
		
	public function createSequence ($sequence=null, $startID=null) {
		$result = 0;
		if ($sequence) {
			$result = $this->adodb->CreateSequence($seqName, $startID);
		}
		return($result);
	}
		
	public function start() {
		return $this->query('START');
	}

	public function savepoint($savepoint='') {
		if ($savepoint) {
			return $this->query('SAVEPOINT ' . $savepoint);
		}
	}

	public function commit() {
		return $this->query('COMMIT');
	}

	public function rollback($savepoint='') {
		return $this->query('ROLLBACK' . ($savepoint ? ' TO SAVEPOINT ' . $savepoint : ''));
	}

	public function escape($value) {
		return $this->adodb->quote();
	}

	public function isError() {
		return $this->adodb->ErrorNo();
	}
		
	public function getErrorMsg() {
		return $this->adodb->ErrorMsg();
	}

	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
	public function __call($name, $args) {
		return call_user_func_array(array($this->adodb, $name), $args);
	}

}
	
	
class A_Db_ADOdblite_Result {
	protected $result;
	public $error;
	public $errmsg;
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function numRows() {
		if ($this->result) {
			return $this->result->RecordCount();
		} else {
			return 0;
		}
	}
		
	public function isError() {
		return $this->error;
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
	
	public function __call($name, $args) {
		return call_user_func_array(array($this->result, $name), $args);
	}

}
	
	
class A_Db_ADOdblite_Recordset extends A_Db_ADOdblite_Result {
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function fetchRow ($mode=null) {
		if ($this->result) {
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			return $this->resultFetchRow();
		}
	}
		
	public function fetchObject ($mode=null) {
		if ($this->result) {
			return $this->FetchNextObject();
		}
	}
		
	public function fetchAll ($class=null) {
		$rows = array();
		if ($this->result) {
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			while ($row = $this->resultFetchRow()) {
				$rows[] = $row;
			}
		}
		return $rows;
	}
		
	public function numRows() {
		if ($this->result) {
			return $this->result->RecordCount();
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return $this->result->FieldCount();
		} else {
			return 0;
		}
	}
	
	public function __call($name, $args) {
		return call_user_func_array(array($this->result, $name), $args);
	}

}
/*
 $db->Affected_Rows()
$db->Close()
$db->Concat($string, $string)
$db->ErrorMsg()
$db->ErrorNo()
$db->Execute($sql, [$inputarray])
$db->GetAll($sql)
$db->GetArray($sql)
$db->IfNull($field, $ifNull)$db->Insert_ID()
$db->Insert_ID()
$db->IsConnected()
$db->qstr($string, [$magic_quotes])
$db->Qmagic($string)
$db->SelectDB($dbname)
$db->SelectLimit( $sql, [nrows], [offset], [$inputarray] ) - Currently MySql/MySqli/MySqlt/SqLite/PostGres7/MsSql/MsSqlpo/Sybase supported
$db->Version()


  Supported Result Set Functions

$ADODB_FETCH_MODE = 'ADODB_FETCH_DEFAULT' | 'ADODB_FETCH_NUM' | 'ADODB_FETCH_ASSOC' | 'ADODB_FETCH_BOTH'

$result->Close()
$result->EOF()
$result->EOF
$result->FetchField($fieldOffset)
$result->FieldCount()
$result->Fields([column])
$result->Fields
$result->GetAll([nRows])
$result->GetArray([nRows])
$result->GetRows([nRows])
$result->Move([row])
$result->MoveFirst()
$result->MoveLast()
$result->MoveNext()
$result->RecordCount()

*/
