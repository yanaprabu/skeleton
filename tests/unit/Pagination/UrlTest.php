<?php

include ('A/Pagination/Url.php');

class Pagination_UrlTest extends UnitTestCase	{

	public function setUp()	{
		$this->url = new A_Pagination_Url ('page.php');
	}

	public function testSingleSetParameter()	{
		$this->url->set('order', 'title');
		$this->assertEqual ($this->url->build(), 'page.php?order=title');
	}

	public function testDoubleSetParameter()	{
		$this->url->set ('order', 'title');
		$this->url->set ('pageSize', '5');
		$this->assertEqual ($this->url->build(), 'page.php?order=title&pageSize=5');
	}

	public function testDoubleSetParameterWithBuildParameter()	{
		$this->url->set ('order', 'title');
		$this->url->set ('pageSize', '5');
		$this->assertEqual ($this->url->build (array ('page' => 2)), 'page.php?page=2&order=title&pageSize=5');
	}

}