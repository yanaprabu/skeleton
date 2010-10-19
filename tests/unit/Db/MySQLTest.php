<?php

class Db_MySQLTest extends UnitTestCase {
	public $config;
	
	function setUp() {
		$this->config = array(
			'SINGLE' => array(
			    'phptype' => 'mysql',
			    'hostspec' => 'localhost',
			    'database' => 'skeletontest1',
			    'username' => 'skeleton',
			    'password' => 'skeleton',
				),
			'MASTER_SLAVE' => array(
			    'config_class' => 'A_Db_Config_Masterslave',
				'master' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'skeletontest1',
					    'username' => 'skeleton',
					    'password' => 'skeleton',
						),
					),
				'slave' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'skeletontest2',
					    'username' => 'skeleton',
					    'password' => 'skeleton',
						),
					),
				),
			'MASTERS_SLAVES' => array(
			    'config_class' => 'A_Db_Config_Masterslave',
				'master' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'skeletontest1',
					    'username' => 'skeleton',
					    'password' => 'skeleton',
						),
					1 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'skeletontest2',
					    'username' => 'skeleton',
					    'password' => 'skeleton',
						),
					),
				'slave' => array(
					0 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'skeletontest1',
					    'username' => 'skeleton',
					    'password' => 'skeleton',
						),
					1 => array(
						'phptype' => 'mysql',
					    'hostspec' => 'localhost',
					    'database' => 'skeletontest2',
					    'username' => 'skeleton',
					    'password' => 'skeleton',
						),
					),
				),
			);
/*
CREATE TABLE `skeletontest1`.`test1` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = InnoDB;
CREATE TABLE `skeletontest2`.`test1` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = InnoDB;
*/
	}
	
	function TearDown() {
	}
	
function testDb_MySQLSingle() {
  		$Db_MySQL = new A_Db_MySQL($this->config['SINGLE']);
		$Db_MySQL->connect();

		$sql = "DELETE FROM test1";
		$Db_MySQL->query($sql);
echo "SQL=$sql, ERROR=".$Db_MySQL->getErrorMsg()."<br/>\n";
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2,'Two')";
		$Db_MySQL->query($sql);
echo "SQL=$sql, ERROR=".$Db_MySQL->getErrorMsg()."<br/>\n";
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');

		$sql = "SELECT id,name FROM test1";
		$result = $Db_MySQL->query($sql);
echo "SQL=$sql, ERROR=".$Db_MySQL->getErrorMsg()."<br/>\n";
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');

		$rows = $result->fetchAll();
dump($rows, 'ROWS: ');
		$diff = array_diff_assoc($rows->toArray(), array(0=>array('id'=>1,'name'=>'One'),1=>array('id'=>2,'name'=>'Two')));
dump($diff, 'DIFF: ');
		$this->assertTrue($diff == array());
		
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');
#		$this->assertFalse(!$result);

		$Db_MySQL->close();
	}
	
	function testDb_MySQLMasterSlave() {
  		$Db_MySQL = new A_Db_MySQL($this->config['MASTER_SLAVE']);
		$Db_MySQL->connect();

		// should go to master
		$sql = "DELETE FROM test1";
		$Db_MySQL->query($sql);
echo "SQL=$sql, ERROR=".$Db_MySQL->getErrorMsg()."<br/>\n";
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');

		// should go to master
		$sql = "INSERT INTO test1 (id,name) VALUES (3,'Three'),(4,'Four')";
		$Db_MySQL->query($sql);
echo "SQL=$sql, ERROR=".$Db_MySQL->getErrorMsg()."<br/>\n";
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');

		// should come from slave
		$sql = "SELECT id,name FROM test1";
		$result = $Db_MySQL->query($sql);
echo "SQL=$sql, ERROR=".$Db_MySQL->getErrorMsg()."<br/>\n";
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');

		$rows = $result->fetchAll();
dump($rows, 'ROWS: ');
		$diff = array_diff_assoc($rows->toArray(), array(0=>array('id'=>1,'name'=>'One'),1=>array('id'=>2,'name'=>'Two')));
dump($diff, 'DIFF: ');
		$this->assertTrue($diff == array());
		
		$this->assertTrue($Db_MySQL->getErrorMsg() == '');
#		$this->assertFalse(!$result);

		$Db_MySQL->close();
	}
	
}
