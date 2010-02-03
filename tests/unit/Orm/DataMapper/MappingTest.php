<?php

class Orm_DataMapper_Mapping_Object	{
	public $item;
	function setItem($item){}
	function set(){}
	function get(){}
}
Mock::Generate('Orm_DataMapper_Mapping_Object', 'Orm_DataMapper_Mapping_MockObject');

class Orm_DataMapper_Mapping_Test extends UnitTestCase	{

	public function setUp()	{
		$this->object = new Orm_DataMapper_Mapping_MockObject();
		$this->setMethod = 'setDescription';
		$this->getMethod = 'getDescription';
		$this->property = 'description';
		$this->column = 'table.description';
		$this->alias = 'description';
		$this->table = 'items';
		$this->mapping = new A_Orm_DataMapper_Mapping(
			$this->getMethod,
			$this->setMethod,
			$this->property,
			'',
			array ($this->alias => $this->column),
			$this->table,
			true
		);
	}

	public function testGetSetMethod()	{
		$this->assertEqual ($this->mapping->getSetMethod(), $this->setMethod);
	}

	public function testSetSetMethod()	{
		$this->mapping->setSetMethod('setTitle');
		$this->assertEqual ($this->mapping->getSetMethod(), 'setTitle');
	}

	public function testGetGetMethod()	{
		$this->assertEqual ($this->mapping->getGetMethod(), $this->getMethod);
	}

	public function testSetGetMethod()	{
		$this->mapping->setGetMethod('getTitle');
		$this->assertEqual ($this->mapping->getGetMethod(), 'getTitle');
	}

	public function testGetProperty()	{
		$this->assertEqual ($this->mapping->getProperty(), $this->property);
	}

	public function testSetProperty()	{
		$this->mapping->setProperty('title');
		$this->assertEqual ($this->mapping->getProperty(), 'title');
	}

	public function testGetColumn()	{
		$this->assertEqual ($this->mapping->getColumn(), $this->column);
	}

	public function testSetColumn()	{
		$this->mapping->setColumn('title');
		$this->assertEqual ($this->mapping->getColumn(), 'title');
	}

	public function testGetAlias()	{
		$this->assertEqual ($this->mapping->getAlias(), $this->alias);
	}

	public function testGetTable()	{
		$this->assertEqual ($this->mapping->getTable(), $this->table);
	}

	public function testIsKey()	{
		$this->assertEqual ($this->mapping->isKey(), true);
	}

	public function testToColumn()	{
		$this->mapping->toColumn(array ('pageTitle' => 'title'), 'page', true);
		$this->assertEqual ($this->mapping->getAlias(), 'pageTitle');
		$this->assertEqual ($this->mapping->getColumn(), 'title');
		$this->assertEqual ($this->mapping->getTable(), 'page');
		$this->assertEqual ($this->mapping->isKey(), true);
	}

	public function testMapToSetMethod()	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$mapping->setSetMethod('setItem');
		$mapping->toColumn('item');
		$this->object->expectOnce('setItem', array('Car'));
		$mapping->loadObject($this->object, array('item' => 'Car'));
	}

	public function testMapToProperty()	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$mapping->setProperty('item');
		$mapping->toColumn('item');
		$mapping->loadObject($this->object, array('item' => 'Car'));
		$this->assertEqual ($this->object->item, 'Car');
	}

	public function testMapToGenericMethods()	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$mapping->setSetMethod('set');
		$mapping->setGetMethod('get');
		$mapping->setProperty('item');
		$mapping->toColumn('item');
		$this->object->expectOnce('set', array('item', 'Car'));
		$mapping->loadObject($this->object, array('item' => 'Car'));
	}

}