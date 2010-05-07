<?php

class DbAbstractClass extends A_Db_Abstract {
	public $connection_set = array();
	
	public function _connect($config=array()) {
#echo 'DbAbstractClass::_connect: <pre>' . print_r($config, 1) . "</pre>\n";
		$this->connection_set = $config;
		return $config['database'];
	}
}

class Db_AbstractTest extends UnitTestCase {
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
			'MASTER_SLAVE' => array(
			    'config_class' => 'A_Db_Config_Masterslave',
				'master' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'master0',
					    'username' => 'master0',
					    'password' => 'master0',
						),
					),
				'slave' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'slave0',
					    'username' => 'slave0',
					    'password' => 'slave0',
						),
					),
				),
			'MASTERS_SLAVES' => array(
			    'config_class' => 'A_Db_Config_Masterslave',
				'master' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'master0',
					    'username' => 'master0',
					    'password' => 'master0',
						),
					1 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'master1',
					    'username' => 'master1',
					    'password' => 'master1',
						),
					),
				'slave' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'slave0',
					    'username' => 'slave0',
					    'password' => 'slave0',
						),
					1 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'slave1',
					    'username' => 'slave1',
					    'password' => 'slave1',
						),
					2 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'slave2',
					    'username' => 'slave2',
					    'password' => 'slave2',
						),
					),
				),
			);
	}
	
	function TearDown() {
	}
	
	function testDb_AbstractSingleConfig() {
		$Db_Abstract = new DbAbstractClass($this->config['SINGLE']);
		
		$Db_Abstract->connect();
		$this->assertTrue($Db_Abstract->connection_set['database'] == 'single');
#echo "ERROR for connection name '{$Db_Abstract->connection_set['database']}': " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
	}
	
	function testDb_AbstractMasterSlaveConfig() {
		$Db_Abstract = new DbAbstractClass($this->config['MASTER_SLAVE']);
		
		$Db_Abstract->connect();
		$this->assertTrue($Db_Abstract->connection_set['database'] == 'slave0');
#echo "ERROR for connection name '{$Db_Abstract->connection_set['database']}': " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
	}
	
	function testDb_AbstractMastersSlavesConfig() {
		$Db_Abstract = new DbAbstractClass($this->config['MASTERS_SLAVES']);
		
		$Db_Abstract->connect();
		$Db_Abstract->connect();
		$this->assertTrue(in_array($Db_Abstract->connection_set['database'], array('slave0','slave1','slave2')));
#echo "ERROR for connection name '{$Db_Abstract->connection_set['database']}': " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
	}
		
	function testDb_AbstractSingleGetConfig() {
		$Db_Abstract = new DbAbstractClass($this->config['SINGLE']);
		
		$config = $Db_Abstract->getConfig('SELECT');		// no connnection names for single
		$this->assertTrue($config['name'] == '');
#echo "ERROR for connection name '{$config['name']}': " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
		
		$config = $Db_Abstract->getConfig('INSERT');
		$this->assertTrue($config['name'] == '');			// no connnection names for single
#echo "ERROR for connection name '{$config['name']}': " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
	}

	function testDb_AbstractMasterSlaveGetConfig() {
		$Db_Abstract = new DbAbstractClass($this->config['MASTER_SLAVE']);
		
		$config = $Db_Abstract->getConfig('SELECT');
		$this->assertTrue($config['name'] == 'slave');
		$this->assertTrue($config['data']['database'] == 'slave0');
#echo "ERROR for {$config['name']}: " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
		
		$config = $Db_Abstract->getConfig('INSERT');
		$this->assertTrue($config['name'] == 'master');
		$this->assertTrue($config['data']['database'] == 'master0');
#echo "ERROR for {$config['name']}: " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
	}
	
	function testDb_AbstractMastersSlavesGetConfig() {
		$Db_Abstract = new DbAbstractClass($this->config['MASTERS_SLAVES']);
		
		$config = $Db_Abstract->getConfig('SELECT');
echo 'DbAbstractClass::getConfig: <pre>' . print_r($config, 1) . "</pre>\n";
		$this->assertTrue($config['name'] == 'slave');
		$this->assertTrue(in_array($config['data']['database'], array('slave0','slave1','slave2')));
#echo "ERROR for {$config['name']}: " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
		
		$config = $Db_Abstract->getConfig('INSERT');
echo 'DbAbstractClass::getConfig: <pre>' . print_r($config, 1) . "</pre>\n";
		$this->assertTrue($config['name'] == 'master');
		$this->assertTrue(in_array($config['data']['database'], array('master0','master1')));
#echo "ERROR for {$config['name']}: " . $Db_Abstract->_getErrorMsg() . "<br/>\n";
	}

}
