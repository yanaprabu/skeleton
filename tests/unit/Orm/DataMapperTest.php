<?php

require_once ('A/Orm/DataMapper.php');
require_once ('A/Orm/Mapping.php');
class DomainObject{}
Mock::Generate ('DomainObject','MockDomainObject');

class Orm_DataMapperTest extends UnitTestCase	{

	public function setUp()	{
		$db = true;
		$this->mapper = new A_Orm_DataMapper ($db, 'MockObject','pages');
		$this->mapper->mapMethods('getTitle','setTitle')->toField('title');
		$this->mapper->mapProperty('description')->toField('description', 'items');
		$this->mapper->mapProperty('body')->toField(array ('body' => 'page_body'));
		$this->object = $this->mapper->load(array(
			'title' => 'Hello',
			'description' => 'Cheesy',
		));
	}

	public function testLoadReturnsCorrectClass()	{
		$this->assertIsA ($this->object, 'MockObject');
	}

	public function testMapMethodsToField()	{
		$this->assertEqual ($this->object->getTitle(), 'Hello');
	}

	public function testMapPropertytoField()	{
		$this->assertEqual ($this->object->description, 'Cheesy');
	}

	public function testGetTableNames()	{
		$this->assertEqual ($this->mapper->getTableNames(), array('pages', 'items'));
	}

	public function testGetFieldNames()	{
		$this->assertEqual ($this->mapper->getFieldNames(), array ('title','description', array ('body' => 'page_body')));
	}

}

class MockObject extends MockDomainObject	{

	public $description;

	public function getTitle()	{
		return $this->title;
	}

	public function setTitle($title)	{
		$this->title = $title;
	}

}