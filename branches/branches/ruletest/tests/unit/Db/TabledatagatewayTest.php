<?php

class A_Db_Tabledatagateway_MockDb {
	public $sql = '';
	
	public function escape($str) {
		return addslashes($str);
	}
	
	public function query($sql) {
		$this->sql = $sql;
		$result = new A_Db_Tabledatagateway_MockDbResult();
		return $result;
	}

	public function isError() {
		return 0;
	}
}

class A_Db_Tabledatagateway_MockDbResult {
	public $rows = array(
				0=>array('id'=>'foo','name'=>'bar'),
				1=>array('id'=>'faz','name'=>'baz'),
				);
	
	public function fetchRow() {
		return next($this->rows);
	}
	
	public function isError() {
		return 0;
	}
}

class Test1 extends A_Db_Tabledatagateway {
	
	public function getKey() {
		return $this->key;
	}
}

class Test2 extends Test1 {
	public function __construct($db, $table='', $key='') {
		parent::__construct($db, 'foo', 'code');
	}
}

class Db_TabledatagatewayTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_TabledatagatewayTable() {
  		$db = new A_Db_Tabledatagateway_MockDb();

   		$tdg = new A_Db_Tabledatagateway($db);
  		$this->assertEqual($tdg->getTable(), 'a_db_tabledatagateway');
  		
  		$tdg = new A_Db_Tabledatagateway($db, 'foo');
  		$this->assertEqual($tdg->getTable(), 'foo');
  		
  		$tdg = new Test1($db);
  		$this->assertEqual($tdg->getTable(), 'test1');
  		
  		$tdg = new Test2($db);
  		$this->assertEqual($tdg->getTable(), 'foo');
  		
	}
	
	function testDb_TabledatagatewayId() {
  		$db = new A_Db_Tabledatagateway_MockDb();
  		
  		$tdg = new Test1($db);
  		$this->assertEqual($tdg->getKey(), 'id');
  		
  		$tdg = new Test1($db, '', 'bar');
  		$this->assertEqual($tdg->getKey(), 'bar');
  		
  		$tdg = new Test2($db);
  		$this->assertEqual($tdg->getKey(), 'code');
  		
	}
	
	function testDb_TabledatagatewaySelect() {
  		$db = new A_Db_Tabledatagateway_MockDb();
  		
  		$tdg = new Test2($db);
  		$result = $tdg->find('bar');
#$this->dump($result, "result=");
  		$this->dump($db->sql, "SQL=");
#		$this->assertEqual($tdg->getTable(), 'foo');
  		
	}
	
}
