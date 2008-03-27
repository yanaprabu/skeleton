<?php
require_once('A/Sql/Expression.php');

class Sql_ExpressionTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_ExpressionNotNull() {
  		$Sql_Expression = new A_Sql_Expression('foo != bar');
  		$this->assertEqual($Sql_Expression->render(), "(foo != bar)");
  				
		$Sql_Expression = new A_Sql_Expression('foo', 'bar');
  		$this->assertEqual($Sql_Expression->render(), "(foo='bar')");
  				
  		$Sql_Expression = new A_Sql_Expression('foo=', 'bar');
  		$this->assertEqual($Sql_Expression->render(), "(foo='bar')");
  				
  		$Sql_Expression = new A_Sql_Expression('foo>=', 'bar');
  		$this->assertEqual($Sql_Expression->render(), "(foo>='bar')");
  				
  		$Sql_Expression = new A_Sql_Expression(array('foo'=>'bar', 'faz'=>42));
  		$this->assertEqual($Sql_Expression->render(), "(foo='bar' AND faz='42')");
	}
	
}
