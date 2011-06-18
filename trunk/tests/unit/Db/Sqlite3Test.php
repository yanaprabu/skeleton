<?php

class Db_Sqlite3Test extends UnitTestCase {
	protected $filename;
	
	function setUp() {
		$this->filename = dirname(__FILE__) . '/sqlite3.db';
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
	
	function testDb_SqliteNoFilename() {
		$Db_Sqlite3 = new A_Db_Sqlite3();
		
		$this->assertFalse($Db_Sqlite3->isError());
		$this->assertTrue($Db_Sqlite3->getErrorMsg() == '');
		
		$Db_Sqlite3->connect();
		$this->assertTrue($Db_Sqlite3->isError());
		$this->assertFalse($Db_Sqlite3->getErrorMsg() == '');
		$Db_Sqlite3->close();
	}
	
	function testDb_SqliteFilename() {
		$Db_Sqlite3 = new A_Db_Sqlite3(array('filename'=>$this->filename));
		$Db_Sqlite3->connect();
		
		$this->assertFalse($Db_Sqlite3->isError());
		$this->assertTrue($Db_Sqlite3->getErrorMsg() == '');
		$Db_Sqlite3->close();
	}
	
	function testDb_SqliteInsertUpdateSelect() {
		$Db_Sqlite3 = new A_Db_Sqlite3(array('filename'=>$this->filename));
		$Db_Sqlite3->connect();
		
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
		$Db_Sqlite3->query($sql);
		$this->assertFalse($Db_Sqlite3->isError());
		$this->assertTrue($Db_Sqlite3->getErrorMsg() == '');
		
		foreach ($test_rows as $row) {
			$result = $Db_Sqlite3->query("INSERT INTO test1 (id, name) VALUES (:id, ':name')", $row);
			$this->assertFalse($Db_Sqlite3->isError());
			$this->assertTrue($Db_Sqlite3->getErrorMsg() == '');
		}
		$result = $Db_Sqlite3->query("SELECT * FROM test1");
#dump($result, 'RESULT: ', 1);
		$this->assertFalse($Db_Sqlite3->isError());
		$this->assertTrue($Db_Sqlite3->getErrorMsg() == '');

		$rows = $result->fetchAll();
#dump($rows, 'ROWS: ', 1);
		foreach ($rows as $n => $row) {
			$this->assertTrue($test_rows[$n]['id'] == $row['id']);
			$this->assertTrue($test_rows[$n]['name'] == $row['name']);
		}
		
#dump($Db_Sqlite3->getSql(), 'SQL HISTORY: ', 1);
		$Db_Sqlite3->close();
	}
	
}
