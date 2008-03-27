<?php
require_once('A/Sql/Groupby.php');

class Sql_GroupbyTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_GroupbyNotNull() {
  		$Sql_Groupby = new A_Sql_Groupby('foo');
  		$this->assertEqual($Sql_Groupby->render(), " GROUP BY foo");
  				
  		$Sql_Groupby = new A_Sql_Groupby('foo', 'bar');
  		$this->assertEqual($Sql_Groupby->render(), " GROUP BY foo, bar");
  				
  		$Sql_Groupby = new A_Sql_Groupby(array('foo', 'bar'));
  		$this->assertEqual($Sql_Groupby->render(), " GROUP BY foo, bar");
  				
	}
	
}
