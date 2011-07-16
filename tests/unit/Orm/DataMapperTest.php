<?php

class DomainObject{}
Mock::Generate ('DomainObject','MockDomainObject');

class Orm_DataMapperTest extends UnitTestCase	{

	public function setUp()	{
		$db = true;
		$this->mapper = new A_Orm_DataMapper ($db, 'MockObject','pages');
		$this->mapper->mapMethods('getTitle','setTitle')->toColumn('title');
		$this->mapper->mapProperty('description')->toColumn('description', 'items');
		$this->mapper->mapProperty('body')->toColumn(array ('body' => 'page_body'));
		$this->object = $this->mapper->load(array(
			'title' => 'Hello',
			'description' => 'Cheesy',
		));
	}

	public function testLoadReturnsCorrectClass()	{
		$this->assertIsA ($this->object, 'MockObject');
	}

	public function testMapMethodsToColumn()	{
		$this->assertEqual ($this->object->getTitle(), 'Hello');
	}

	public function testMapPropertytoColumn()	{
		$this->assertEqual ($this->object->description, 'Cheesy');
	}

	public function testGetTableNames()	{
		$this->assertEqual ($this->mapper->getTables(), array('pages', 'items'));
	}

	public function testGetFieldNames()	{
		$this->assertEqual ($this->mapper->getColumns(), array ('pages.title','items.description', 'pages.page_body AS body'));
	}

}

class MockObject extends MockDomainObject	{

	public $description;
	public $body;

	public function getTitle()	{
		return $this->title;
	}

	public function setTitle($title)	{
		$this->title = $title;
	}

}