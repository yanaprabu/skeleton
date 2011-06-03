<?php

class Sql_OrderbyTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_OrderbyNotNull() {
  		$Sql_Orderby = new A_Sql_Orderby('foo');
  		$this->assertEqual($Sql_Orderby->render(), " ORDER BY foo");
  				
  		$Sql_Orderby = new A_Sql_Orderby('foo', 'bar');
  		$this->assertEqual($Sql_Orderby->render(), " ORDER BY foo, bar");
  				
  		$Sql_Orderby = new A_Sql_Orderby(array('foo', 'bar'));
  		$this->assertEqual($Sql_Orderby->render(), " ORDER BY foo, bar");
  			}
	
}
