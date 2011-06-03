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
	
/*function testDb_MySQLSingle() {
  		$db = new A_Db_MySQL($this->config['SINGLE']);
		$db->connect();

		$sql = "DELETE FROM test1";
		$db->query($sql);
echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2,'Two')";
		$db->query($sql);
echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$sql = "SELECT id,name FROM test1";
		$result = $db->query($sql);
echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$rows = $result->fetchAll();
dump($rows, 'ROWS: ');
		$diff = array_diff_assoc($rows->toArray(), array(0=>array('id'=>1,'name'=>'One'),1=>array('id'=>2,'name'=>'Two')));
dump($diff, 'DIFF: ');
		$this->assertTrue($diff == array());
		
		$this->assertTrue($db->getErrorMsg() == '');
#		$this->assertFalse(!$result);

		$db->close();
	}
	
	function testDb_MySQLMasterSlave() {
  		$db = new A_Db_MySQL($this->config['MASTER_SLAVE']);
		$db->connect();

		// should go to master
		$sql = "DELETE FROM test1";
		$db->query($sql);
echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		// should go to master
		$sql = "INSERT INTO test1 (id,name) VALUES (3,'Three'),(4,'Four')";
		$db->query($sql);
echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		// should come from slave
		$sql = "SELECT id,name FROM test1";
		$result = $db->query($sql);
echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$rows = $result->fetchAll();
dump($rows, 'ROWS: ');
		$diff = array_diff_assoc($rows->toArray(), array(0=>array('id'=>1,'name'=>'One'),1=>array('id'=>2,'name'=>'Two')));
dump($diff, 'DIFF: ');
		$this->assertTrue($diff == array());
		
		$this->assertTrue($db->getErrorMsg() == '');
#		$this->assertFalse(!$result);

		$db->close();
	}*/
	
	function testDb_MySQLFetchRow() {
		$db = new A_Db_MySQL($this->config['SINGLE']);
		$db->connect();

		$sql = "DELETE FROM test1";
		$db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
				
		$expect_rows = array(
			0 => array('id'=>1,'name'=>'One'),
			1 => array('id'=>2,'name'=>'Two'),
			2 => array('id'=>3,'name'=>'Three'),
			3 => array('id'=>4,'name'=>'Four'),
			);
		$i = 0;
		while ($row = $result->fetchRow()) {
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}
		
		$this->assertTrue($db->getErrorMsg() == '');

		$db->close();
	}
	
	function testDb_MySQLFetchAll() {
		$db = new A_Db_MySQL($this->config['SINGLE']);
		$db->connect();

		$sql = "DELETE FROM test1";
		$db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
				
		$expect_rows = array(
			0 => array('id'=>1,'name'=>'One'),
			1 => array('id'=>2,'name'=>'Two'),
			2 => array('id'=>3,'name'=>'Three'),
			3 => array('id'=>4,'name'=>'Four'),
			);
		$i = 0;
		$result->fetchAll();
		foreach ($result->toArray() as $row) {
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}
		
		$this->assertTrue($db->getErrorMsg() == '');

		$db->close();
	}

	function testDb_MySQLIterator() {
		$db = new A_Db_MySQL($this->config['SINGLE']);
		$db->connect();

		$sql = "DELETE FROM test1";
		$db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
				
		$expect_rows = array(
			0 => array('id'=>1,'name'=>'One'),
			1 => array('id'=>2,'name'=>'Two'),
			2 => array('id'=>3,'name'=>'Three'),
			3 => array('id'=>4,'name'=>'Four'),
			);
		$i = 0;
		foreach ($result as $key => $row) {
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}
		
		$this->assertTrue($db->getErrorMsg() == '');

		$db->close();
	}
	
	function testDb_MySQLIteratorGather() {
		$db = new A_Db_MySQL($this->config['SINGLE']);
		$db->connect();

		$sql = "DELETE FROM test1";
		$db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $db->query($sql);
#echo "SQL=$sql, ERROR=".$db->getErrorMsg()."<br/>\n";
		$this->assertTrue($db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
				
		$expect_rows = array(
			0 => array('id'=>1,'name'=>'One'),
			1 => array('id'=>2,'name'=>'Two'),
			2 => array('id'=>3,'name'=>'Three'),
			3 => array('id'=>4,'name'=>'Four'),
			);
		$i = 0;
dump($result, 'RESULT BEFORE 1ST FOREACH: ', 1);
		$result->enableGather();
		foreach ($result as $key => $row) {
dump($row, "ROW $i: $key => ", 1);
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}

		$i = 0;
dump($result, 'RESULT AFTER 1ST FOREACH: ', 1);
		foreach ($result as $key => $row) {
dump($row, "ROW $i: $key => ", 1);
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}
dump($result, 'RESULT AFTER 2ND FOREACH: ', 1);
		
		$this->assertTrue($db->getErrorMsg() == '');

		$db->close();
	}
	
}
