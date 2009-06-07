<?php

require_once ('A/Orm/DataMapper.php');
require_once ('A/Orm/Mapping.php');

class DomainObject{}
Mock::Generate ('DomainObject','MockObject');

class Orm_DataMapperTest extends UnitTestCase	{

	public function setUp()	{
		$this->mapper = new A_Orm_DataMapper();
		$this->mapper->setClass('MockObject');
		$this->mapper->mapMethods('getTitle','setTitle')->toField('title');
	}

	public function testLoadReturnsCorrectClass()	{
		$this->assertIsA ($this->mapper->load(array('title' => 'Hello')), 'MockObject');
	}

}