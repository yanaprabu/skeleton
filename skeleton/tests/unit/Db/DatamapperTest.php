<?php
require_once('A/Db/Datamapper.php');

class Db_DatamapperTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_DatamapperNotNull() {
  		$db = null;
  		$class_name = 'Foo';
  		$table_name = 'bar';
  		$Db_Datamapper = new A_Db_Datamapper($db, $class_name, $table_name);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
