<?php

class DbAdapterClass extends A_Db_Adapter
{

	public $test_config = array();
	
	public function _connect()
	{
		$this->test_config = $this->_config;
		$this->_connection = 'xyz';
	}
	
	protected function _close()
	{}
	
	protected function _query($sql)
	{}
	
	protected function _lastId()
	{}

	protected function _selectDb($database)
	{}

	public function limit($sql, $count, $offset='')
	{}

}

class Db_AdapterTest extends UnitTestCase
{

	protected $config;
	
	function setUp()
	{
		$this->config = array(
			
			// not meant to be a real connection, no connection actually made
			'SINGLE' => array(
				'phptype' => 'mysql',
				'hostspec' => 'localhost',
				'database' => 'single',
				'username' => 'single',
				'password' => 'single',
			),
		);
	}
	
	function testDb_AdapterConstruct()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->test_config['database'], 'single');
	}
	
	public function testGetConnection()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		$Db_Adapter->connect();
		
		$this->assertEqual($Db_Adapter->getConnection(), 'xyz');
	}

}

