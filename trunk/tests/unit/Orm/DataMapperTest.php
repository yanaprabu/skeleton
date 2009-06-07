<?php

require_once ('A/Orm/DataMapper.php');
require_once ('A/Orm/Mapping.php');
class DomainObject{}
Mock::Generate ('DomainObject','MockDomainObject');

class Orm_DataMapperTest extends UnitTestCase	{

	public function setUp()	{
		$this->mapper = new A_Orm_DataMapper();
		$this->mapper->setClass('MockObject');
		$this->mapper->mapMethods('getTitle','setTitle')->toField('title');
		$this->mapper->mapProperty('description')->toField('description');
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