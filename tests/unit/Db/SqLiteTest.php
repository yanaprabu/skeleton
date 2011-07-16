<?php

class Db_SqliteTest extends UnitTestCase {
	protected $filename;
	
	function setUp() {
		$this->filename = dirname(__FILE__) . '/sqlite2.db';
#echo "filename={$this->filename}<br/>";
		if (file_exists($this->filename)) {
			unlink($this->filename);
		}
	}
	
	function TearDown() {
		if (file_exists($this->filename)) {
			unlink($this->filename);
		}
	}
	
	function testDb_SqLiteNoFilename() {
		$Db_Sqlite = new A_Db_Sqlite();
		
		$this->assertFalse($Db_Sqlite->isError());
		$this->assertTrue($Db_Sqlite->getErrorMsg() == '');
		
		$Db_Sqlite->connect();
		$this->assertTrue($Db_Sqlite->isError());
		$this->assertFalse($Db_Sqlite->getErrorMsg() == '');
		$Db_Sqlite->close();
	}
	
	function testDb_SqLiteFilename() {
		$Db_Sqlite = new A_Db_Sqlite(array('filename'=>$this->filename));
		$Db_Sqlite->connect();
		
		$this->assertFalse($Db_Sqlite->isError());
		$this->assertTrue($Db_Sqlite->getErrorMsg() == '');
		$Db_Sqlite->close();
	}
	
	function testDb_SqLiteInsertUpdateSelect() {
		$Db_Sqlite = new A_Db_Sqlite(array('filename'=>$this->filename));
		$Db_Sqlite->connect();
		
		$test_rows = array(
			0 => array(
				':id' => 10,
				':name' => 'Foo',
				),
			1 => array(
				':id' => 20,
				':name' => 'Bar',
				),
			);
		$sql = "CREATE TABLE test1 (id INT, name VARCHAR(100))";
		$Db_Sqlite->query($sql);
		$this->assertFalse($Db_Sqlite->isError());
		$this->assertTrue($Db_Sqlite->getErrorMsg() == '');
		
		foreach ($test_rows as $row) {
			$result = $Db_Sqlite->query("INSERT INTO test1 (id, name) VALUES (:id, ':name')", $row);
			$this->assertFalse($Db_Sqlite->isError());
			$this->assertTrue($Db_Sqlite->getErrorMsg() == '');
		}
		$result = $Db_Sqlite->query("SELECT * FROM test1");
#dump($result, 'RESULT: ', 1);
		$this->assertFalse($Db_Sqlite->isError());
		$this->assertTrue($Db_Sqlite->getErrorMsg() == '');

		$rows = $result->fetchAll();
#dump($rows, 'ROWS: ', 1);
		foreach ($rows as $n => $row) {
			$this->assertTrue($test_rows[$n][':id'] == $row['id']);
			$this->assertTrue($test_rows[$n][':name'] == $row['name']);
		}
		
#dump($Db_Sqlite->getSql(), 'SQL HISTORY: ', 1);
		$Db_Sqlite->close();
	}
	
}
