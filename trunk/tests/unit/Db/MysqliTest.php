<?php

class Db_MysqliTest extends UnitTestCase
{
	public $db;
	public $config = array(
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
	
	public function setUp()
	{
  		$this->db = new A_Db_Mysqli($this->config['SINGLE']);
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
	
	public function TearDown() {
		$this->db->close();
	}
	
/*
	function testDb_Single() {
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
	}
	
	function testDb_MasterSlave() {
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
	}
 */
	
	public function testDb_FetchRow() {
		$this->db->connect();

		$sql = "DELETE FROM test1";
		$this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
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
		
		$this->assertTrue($this->db->getErrorMsg() == '');
	}
	
	public function testDb_FetchAll() {
		$this->db->connect();

		$sql = "DELETE FROM test1";
		$this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
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
		
		$this->assertTrue($this->db->getErrorMsg() == '');
	}

	public function testDb_Iterator() {
		$this->db->connect();

		$sql = "DELETE FROM test1";
		$this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
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
		
		$this->assertTrue($this->db->getErrorMsg() == '');
	}
	
	public function testDb_IteratorGather() {
		$this->db->connect();

		$sql = "DELETE FROM test1";
		$this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');

		$sql = "INSERT INTO test1 (id,name) VALUES (1,'One'),(2, 'Two'),(3,'Three'),(4, 'Four')";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
		
		$sql = "SELECT id,name FROM test1";
		$result = $this->db->query($sql);
#echo "SQL=$sql, ERROR=".$this->db->getErrorMsg()."<br/>\n";
		$this->assertTrue($this->db->getErrorMsg() == '');
		$this->assertTrue($result->numRows() == 4);
		$this->assertTrue($result->getErrorMsg() == '');
				
		$expect_rows = array(
			0 => array('id'=>1,'name'=>'One'),
			1 => array('id'=>2,'name'=>'Two'),
			2 => array('id'=>3,'name'=>'Three'),
			3 => array('id'=>4,'name'=>'Four'),
			);
		$i = 0;
#dump($result, 'RESULT BEFORE 1ST FOREACH: ', 1);
		$result->enableGather();
		foreach ($result as $key => $row) {
#dump($row, "ROW $i: $key => ", 1);
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}

		$i = 0;
#dump($result, 'RESULT AFTER 1ST FOREACH: ', 1);
		foreach ($result as $key => $row) {
#dump($row, "ROW $i: $key => ", 1);
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}
#dump($result, 'RESULT AFTER 2ND FOREACH: ', 1);
		
		$this->assertTrue($this->db->getErrorMsg() == '');
	}

	public function testDb_PrepareArray() {
		$this->db->connect();

		$sql = "SELECT id,name FROM test1 WHERE id>? AND name LIKE ?";
#		$sql = "SELECT id,name FROM test1 WHERE id>?";

		// with and without : before tags
		$result = $this->db->query($sql, array(1, 'T%'));	//array(1));
#dump($this->db->getSql(), 'getSql: ', 1);
#echo "ERROR=" . $this->db->getErrorMsg() . "<br/>";
		$this->assertTrue($this->db->getErrorMsg() == '');
		$expect_rows = array(
			0 => array('id'=>2,'name'=>'Two'),
			1 => array('id'=>3,'name'=>'Three'),
			);
		$i = 0;
		foreach ($result as $key => $row) {
#dump($row, "row $i:", 1);
			$diff = array_diff_assoc($row, $expect_rows[$i]);
			$this->assertTrue($diff == array());
			++$i;
		}

	}
	
}
