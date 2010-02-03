<?php

Mock::Generate ('A_Db_MySQL', 'Pagination_Adapter_MockDb');
Mock::Generate ('A_Db_MySQL_RecordSet', 'Pagination_Adapter_MockResult');

class Pagination_Adapter_DbTest extends UnitTestCase	{

	public function setUp()	{
		$this->db = new Pagination_Adapter_MockDb();
		$this->result = new Pagination_Adapter_MockResult();
		$this->db->setReturnValue ('query', $this->result);
		$this->db->setReturnValue ('limit', $this->result);
		$this->row = array ('one', 'two', 'three', 'four');
		$this->query = 'SELECT * FROM pages';
		$this->adapter = new A_Pagination_Adapter_Db ($this->db, $this->query);
	}

	public function testGetNumItemsDelegatesToDatasource()	{
		$this->db->expectOnce ('query');
		$this->adapter->getNumItems();
	}

	public function testGetNumItemsReplacesAsteriskWithCount()	{
		$this->db->expectOnce ('query', array ('SELECT COUNT(*) AS count FROM pages'));
		$this->adapter->getNumItems();
	}

	public function testGetNumItemsReturnsValueFromDatasource()	{
		$this->result->setReturnValue ('isError', false);
		$this->result->setReturnValue ('fetchRow', array ('count' => 10));
		$this->assertEqual ($this->adapter->getNumItems(), 10);
	}

	public function testGetNumItemsResultIsErrorReturnsNull()	{
		$this->result->setReturnValue ('isError', true);
		$this->result->setReturnValue ('fetchRow', array ('count' => 10));
		$this->assertNull ($this->adapter->getNumItems());
	}

	public function testGetNumItemsNullRowReturnsNull()	{
		$this->result->setReturnValue ('isError', false);
		$this->result->setReturnValue ('fetchRow', null);
		$this->assertNull ($this->adapter->getNumItems());
	}

	public function testGetItemsDelegatesToDatasource()	{
		$this->db->expectOnce ('limit', array ($this->query, 10, 10));
		$this->adapter->getItems (10, 10);
	}

	public function testGetItemsReturnsValueFromDataSource()	{
		$this->result->setReturnValue ('isError', false);
		$this->result->setReturnValue ('numRows', 10);
		$this->result->setReturnValueAt (1, 'fetchRow', $this->row);
		$this->assertNotNull ($this->adapter->getItems (10, 10));
	}

	public function testGetItemsResultIsErrorReturnsNull()	{
		$this->result->setReturnValue ('isError', true);
		$this->result->setReturnValue ('numRows', 10);
		$this->result->setReturnValueAt (1, 'fetchRow', $this->row);
		$this->assertNull ($this->adapter->getItems (10, 10));
	}

	public function testGetItemsNoRowsReturnsNull()	{
		$this->result->setReturnValue ('isError', false);
		$this->result->setReturnValue ('numRows', 0);
		$this->result->setReturnValueAt (1, 'fetchRow', $this->row);
		$this->assertNull ($this->adapter->getItems (10, 10));
	}

	public function testSetOrderByFieldAscending()	{
		$this->adapter->setOrderByField ('title');
		$this->db->expectOnce ('limit', array ($this->query . ' ORDER BY title ASC', 10, 10));
		$this->adapter->getItems (10, 10);
	}

	public function testSetOrderByFieldDescending()	{
		$this->adapter->setOrderByField ('title', 1);
		$this->db->expectOnce ('limit', array ($this->query . ' ORDER BY title DESC', 10, 10));
		$this->adapter->getItems (10, 10);
	}

}