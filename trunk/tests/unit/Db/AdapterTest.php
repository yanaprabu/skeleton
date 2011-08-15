<?php

class DbAdapterClass extends A_Db_Adapter
{

	const DEFAULT_DB = null;
	const CONNECTION = 'xyz';
	
	public $test_config = array();
	public $db = self::DEFAULT_DB;
	
	public function _connect()
	{
		$this->test_config = $this->_config;
		$this->_connection = self::CONNECTION;
	}
	
	protected function _close()
	{}
	
	protected function _query($sql)
	{}
	
	protected function _lastId()
	{}

	protected function _selectDb($database)
	{
		$this->db = $database;
	}

	public function limit($sql, $count, $offset='')
	{}
	
	// testing methods
	
	public function getCurrentDatabase()
	{
		return $this->_currentDatabase;
	}

}

class Db_AdapterTest extends UnitTestCase
{

	const ALT_DB = 'foo';
	const ALT_USER = 'bar';
	const ALT_CONNECTION = 'baz';
	
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
		$Db_Adapter = $this->createSingle();
		
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->test_config['database'], $this->config['SINGLE']['database']);
	}
	
	public function testDb_AdapterConfig()
	{
		$Db_Adapter = $this->createSingle();
		$Db_Adapter->config(array('username' => self::ALT_USER));
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->test_config['database'], $this->config['SINGLE']['database'], '\'database\' must NOT be overwritten/deleted');
		$this->assertEqual($Db_Adapter->test_config['username'], self::ALT_USER, '\'username\' index must be overwritten');
	}
	
	public function testDb_AdapterConstructConnection()
	{
		$config = $this->config['SINGLE'];
		$config['connection'] = self::ALT_CONNECTION;
		$Db_Adapter = new DbAdapterClass($config);
		
		$this->assertEqual($Db_Adapter->getConnection(), self::ALT_CONNECTION);
	}
	
	public function testDb_AdapterAutoconnect()
	{
		$Db_Adapter = $this->createSingle();
		
		$this->assertIdentical($Db_Adapter->getConnection(), false);
		$Db_Adapter->query('dudquery');
		$this->assertEqual($Db_Adapter->getConnection(), DbAdapterClass::CONNECTION);
	}
	
	public function testDb_AdapterAutoSelectDb()
	{
		$Db_Adapter = $this->createSingle();
		
		$this->assertIdentical($Db_Adapter->getCurrentDatabase(), null, 'Initial status of database must be null');
		$this->assertEqual($Db_Adapter->db, DbAdapterClass::DEFAULT_DB, 'Database must not be called before connect()');
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->db, $this->config['SINGLE']['database'], 'Database not passed correct name, or not called at all');
		$this->assertEqual($Db_Adapter->getCurrentDatabase(), $this->config['SINGLE']['database'], 'Current database not updated properly');
	}
	
	public function testDb_AdapterSelectDbDefault()
	{
		$Db_Adapter = $this->createSingle();
		$Db_Adapter->connect();
		$Db_Adapter->config(array('database' => self::ALT_DB));
		$Db_Adapter->selectDb();
		
		$this->assertNotEqual($Db_Adapter->db, $this->config['SINGLE']['database'], 'New schema not selected');
		$this->assertEqual($Db_Adapter->db, self::ALT_DB, 'Schema not changed properly');
		$this->assertEqual($Db_Adapter->getCurrentDatabase(), self::ALT_DB, 'Current database not updated properly');
	}
	
	public function testDb_AdapterSelectDbPreConnect()
	{
		$Db_Adapter = $this->createSingle();
		$Db_Adapter->selectDb(self::ALT_DB);
		$this->assertEqual($Db_Adapter->db, DbAdapterClass::DEFAULT_DB, 'Database must not be called before connect()');
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->db, self::ALT_DB, 'Database not passed correct name, or not called at all');
		$this->assertEqual($Db_Adapter->getCurrentDatabase(), self::ALT_DB, 'Current database not updated properly');
	}
	
	public function testDb_AdapterSelectDbPostConnect()
	{
		$Db_Adapter = $this->createSingle();
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->db, $this->config['SINGLE']['database'], 'Database not passed correct name, or not called at all');
		$this->assertEqual($Db_Adapter->getCurrentDatabase(), $this->config['SINGLE']['database'], 'Current database not updated properly');
		$Db_Adapter->selectDb(self::ALT_DB);
		$this->assertEqual($Db_Adapter->db, self::ALT_DB, 'Secondary database select not executed');
		$this->assertEqual($Db_Adapter->getCurrentDatabase(), self::ALT_DB, 'Current database not updated properly');
	}
	
	public function testDb_AdapterSelectDbConservative()
	{
		$Db_Adapter = $this->createSingle();
		$Db_Adapter->connect();
		// reset mock database to detect changes
		$Db_Adapter->db = DbAdapterClass::DEFAULT_DB;
		$Db_Adapter->selectDb($this->config['SINGLE']['database']);
		$this->assertIdentical($Db_Adapter->db, DbAdapterClass::DEFAULT_DB, 'Database called when correct database was already selected');
	}
	
	public function testDb_AdapterGetConnection()
	{
		$Db_Adapter = $this->createSingle();
		
		$this->assertIdentical($Db_Adapter->getConnection(), false);
		$Db_Adapter->connect();
		$this->assertEqual($Db_Adapter->getConnection(), DbAdapterClass::CONNECTION);
	}
	
	public function testDb_AdapterIsConnected()
	{
		$Db_Adapter = $this->createSingle();
		
		$this->assertIdentical($Db_Adapter->isConnected(), false, 'Connection already being made somehow, or registering a false positive');
		$Db_Adapter->connect();
		$this->assertIdentical($Db_Adapter->isConnected(), true, 'Connection not recognized');
	}
	
	private function createSingle()
	{
		return new DbAdapterClass($this->config['SINGLE']);
	}

}

