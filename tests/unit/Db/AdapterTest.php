<?php

class DbAdapterClass extends A_Db_Adapter {
	public $test_config = array();
	
	public function _connect() {
		$this->test_config = $this->_config;
	}

	protected function _close() {		
	}

	protected function _query($sql) {		
	}

	protected function _lastId() {		
	}

	protected function _selectDb($database) {		
	}

	public function limit($sql, $count, $offset='') {		
	}
}

class Db_AdapterTest extends UnitTestCase {
	protected $config;
	
	function setUp() {
		$this->config = array(
			'SINGLE' => array(
			    'phptype' => 'mysql',
			    'hostspec' => 'localhost',
			    'database' => 'single',
			    'username' => 'single',
			    'password' => 'single',
				),
			);
	}
	
	function TearDown() {
	}
	
	function testDb_AdapterConstruct() {
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->test_config['database'], 'single');
#echo "ERROR for connection name '{$Db_Adapter->connection_set['database']}': " . $Db_Adapter->_getErrorMsg() . "<br/>\n";
	}

}
