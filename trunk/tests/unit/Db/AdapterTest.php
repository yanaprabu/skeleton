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
	
	public function testDb_AdapterConstructConfiguration()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->test_config['database'], 'single');
	}
	
	public function testDb_AdapterConstructConnection()
	{
		$connection = 'foo';
		$config = $this->config['SINGLE'];
		$config['connection'] = $connection;
		$Db_Adapter = new DbAdapterClass($config);
		
		$this->assertEqual($Db_Adapter->getConnection(), $connection);
	}
	
	public function testDb_AdapterAutoconnect()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$this->assertIdentical($Db_Adapter->getConnection(), false);
		$Db_Adapter->query('foo');
		$this->assertEqual($Db_Adapter->getConnection(), 'xyz');
	}
	
	public function testDb_AdapterGetConnection()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$this->assertIdentical($Db_Adapter->getConnection(), false);
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->getConnection(), 'xyz');
	}
	
	public function testDb_AdapterIsConnected()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$this->assertIdentical($Db_Adapter->isConnected(), false, 'Connection already being made somehow, or registering a false positive');
		$Db_Adapter->connect();
		$this->assertIdentical($Db_Adapter->isConnected(), true, 'Connection not recognized');
	}

}

