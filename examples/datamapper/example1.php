<?php
include 'config.php';
include 'A/Db/Datamapper.php';
include 'A/Db/Datamapper/Xml.php';

class Mock_Db {
	public $_data = array();
	public $error = 0;

	public function __construct() {
// put some test data into the mapper for this example
		$this->_data = array(
			'Steve' => array(
				'id' => 1,
				'userid' => 'Steve',
				'passwd' => 'lollypop',
				'inactive' => 'N',
				'dept_field' => 'North',
		),
			'Sally' => array(
				'id' => 2,
				'userid' => 'Sally',
				'passwd' => 'sallybop',
				'inactive' => 'N',
				'dept_field' => 'East',
		),
			'Sam' => array(
				'id' => 3,
				'userid' => 'Sam',
				'passwd' => 'sammydop',
				'inactive' => 'Y',
				'dept_field' => 'West',
		),
			);

	}
	
	public function query($sql) {
	     switch (substr($sql, 0, 6)) {
	     case 'SELECT':
			// get value from "WHERE key='value' at end of SQL
			$a = split('WHERE', $sql);
			$a = split('=', $a[1]);
			$key = trim($a[1], " ='");
			if (isset($this->_data[$key])) {
				$row = $this->_data[$key];
			} else {
				$row = array();
				$this->error = 1;
			}
			$result = new  Mock_Db_Result($row);
			break;

		case 'UPDATE':
echo "UPDATE: $sql<br/>";
			$result = null;
			break;
	       
		case 'INSERT':
		case 'REPLACE':
echo "INSERT/REPLACE: $sql<br/>";
			$result = null;
			break;

		case 'DELETE':
echo "DELETE: $sql<br/>";
			$result = null;
			break;
		}     

		return $result;
	}
	
	public function quoteValue($value) {
		return "'$value'";
	}
	
	public function escape($value) {
		return addslashes($value);
	}
	
	public function lastId() {
		return 1;
	}
	
	public function isError() {
		return $this->error;
	}
	
}

class Mock_Db_Result {
	public $_row = array();	// debug data for this example
	public $error = 0;

	public function  __construct($row) {
// put some test data into the mapper for this example
		if ($row) {
			$this->_row = $row;
		} else {
			$this->error = 1;
		}
	}
	
	public function fetchRow() {
	     return $this->_row;
	}
	
	public function isError() {
		return $this->error;
	}
	
}

class User_Mapper extends A_Db_Datamapper {

	public function __construct($db) {
		$this->setDb($db);
		$this->setClass('User');
		$this->setTable('users');
		$this->addMapping(new A_Db_Datamapper_Mapping('username', 'userid', 'string', 20, true, '', array()));
		$this->addMapping(new A_Db_Datamapper_Mapping('password', 'passwd', 'string', 24, false, '', array()));
		$this->addMapping(new A_Db_Datamapper_Mapping('active', 'inactive', 'string', 1, false, '', array()));
		// uncomment these two lines and comment previous line to show join generation
		$this->addMapping(new A_Db_Datamapper_Mapping('dept', 'dept_field', 'string', 1, false, 'company', array()));
		$this->addJoin(new A_Db_Datamapper_Join('users', 'userid', 'company', 'users_id', 'LEFT'));
	}
}

class User {
	public $username = '';
	public $password = '';
	public $active = false;
	public $dept = '';
	
	public function __construct($username='', $password='', $active='', $dept='') {
		$this->username = $username;
		$this->password = $password;
		$this->active = $active;
		$this->dept = $dept;
	}
}



// there are several ways to configure the mapper
#$Mapper = new A_Db_Datamapper(new Mock_Db(), 'User', 'users');	// need to add mappings like in User_Mapper
$Mapper = new A_Db_Datamapper_Xml(new Mock_Db(), 'mapping01.xml');
#$Mapper = new User_Mapper(new Mock_Db());
#$Mapper->allowKeyChanges(false);		// allow the key to be changed in loaded properties

// load() fetches a database record by the key
$User1 = $Mapper->load('Steve');
$User2 = $Mapper->load('Sally');
$User3 = $Mapper->load('Sam');

// calling load90 with an already loaded key will return the object already in memory
$User4 = $Mapper->load('Steve');

// objects can then be used normally
$User1->username = 'adsf';
$User2->password = 'xxxxx';
$User2->active = 'Y';
$User3->active = 'N';
$User4->active = 'Y';

// new objects can be added that will be inserted later
$User5 = $Mapper->add(new User('Stephanie', 'kaboom', 'Y', 'South'), false);

unset($User3);
// commit will generate SQL and then all db object if present
$Mapper->commit();

// getSQL will return an array of SQL UPDATE/INSERT statements to write the changes back to the database
#dump($Mapper->getSQL());

dump($Mapper, 'Mapper'); 
