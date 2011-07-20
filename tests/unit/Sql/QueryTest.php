<?php

class Sql_QueryTest extends UnitTestCase
{

	private $query;
	
	public function setUp()
	{
		$this->query = new A_Sql_Query;
	}
	
	public function testSelect()
	{
		$select = $this->query->select();
		$this->assertTrue($select instanceOf A_Sql_Select);
	}
	
	public function testInsert()
	{
		$columnList = array('foo', 'bar', 'baz');
		$insert = $this->query->insert('footable', $columnList);
		
		$reflection = new ReflectionObject($insert);
		$reflectedProperty = $reflection->getProperty('table');
		$reflectedProperty->setAccessible(true);
		$from = $reflectedProperty->getValue($insert);
		
		$this->assertTrue(in_array('footable', $from->getTables()));
		
		$reflection = new ReflectionObject($insert);
		$reflectedProperty = $reflection->getProperty('columns');
		$reflectedProperty->setAccessible(true);
		$columns = $reflectedProperty->getValue($insert);
		
		$this->assertEqual($columns->getColumns(), $columnList);
	}
	
	public function testUpdate()
	{
		$columnList = array('foo', 'bar', 'baz');
		// TODO add third WHERE condition argument, and assert it
		$update = $this->query->update('footable', $columnList);
		
		$reflection = new ReflectionObject($update);
		$reflectedProperty = $reflection->getProperty('table');
		$reflectedProperty->setAccessible(true);
		$from = $reflectedProperty->getValue($update);
		
		$this->assertTrue(in_array('footable', $from->getTables()));
		
		// TODO add assert for the columns set
	}
	
	public function testDelete()
	{
		// TODO add second WHERE condition argument, and assert it
		$delete = $this->query->delete('footable');
		
		$reflection = new ReflectionObject($delete);
		$reflectedProperty = $reflection->getProperty('table');
		$reflectedProperty->setAccessible(true);
		$from = $reflectedProperty->getValue($delete);
		
		$this->assertTrue(in_array('footable', $from->getTables()));
	}

}
