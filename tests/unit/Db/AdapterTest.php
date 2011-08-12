<?php

class DbAdapterClass extends A_Db_Adapter
{

	const VALID_CONNECTION = '8186CD02-F17D-414E-A08C-FFB73F7F15EB';
	const INVALID_CONNECTION = '201A49D1-4027-49E2-A8EA-D04C712A967C';
	
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
	
	protected function _isConnection($connection)
	{
		return $connection == self::VALID_CONNECTION;
	}
	
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
	
	public function testDb_AdapterConstructValidConnection()
	{
		$Db_Adapter = new DbAdapterClass(DbAdapterClass::VALID_CONNECTION);
		$this->assertEqual($Db_Adapter->getConnection(), DbAdapterClass::VALID_CONNECTION);
	}
	
	public function testDb_AdapterConstructInvalidConnection()
	{
		$Db_Adapter = new DbAdapterClass(DbAdapterClass::INVALID_CONNECTION);
		$this->assertEqual($Db_Adapter->getConnection(), false);
	}
	
	public function testDb_AdapterGetConnection()
	{
		$Db_Adapter = new DbAdapterClass($this->config['SINGLE']);
		
		$this->assertEqual($Db_Adapter->getConnection(), false);
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->getConnection(), 'xyz');
	}

}

