<?php

class Pagination_Helper_UrlTest extends UnitTestCase	{

	public function setUp()	{
		$this->url = new A_Pagination_Helper_Url ('forums.devnetwork.net/page.php', 'http');
	}

	public function testSingleSetParameter()	{
		$this->url->set('order', 'title');
		$this->assertEqual ($this->url->render (), 'http://forums.devnetwork.net/page.php?order=title');
	}

	public function testDoubleSetParameter()	{
		$this->url->set ('order', 'title');
		$this->url->set ('pageSize', '5');
		$this->assertEqual ($this->url->render (), 'http://forums.devnetwork.net/page.php?order=title&pageSize=5');
	}

	public function testDoubleSetParameterWithBuildParameter()	{
		$this->url->set ('order', 'title');
		$this->url->set ('pageSize', '5');
		$this->assertEqual ($this->url->render ('', array ('page' => 2)), 'http://forums.devnetwork.net/page.php?order=title&pageSize=5&page=2');
	}

	public function testSetBase()	{
		$this->url->setBase ('www.devnetwork.net');
		$this->assertEqual ($this->url->render (), 'http://www.devnetwork.net');
	}

	public function testSetBaseParam()	{
		$this->assertEqual ($this->url->render ('page.php'), 'page.php');
	}

	public function testSetProtocol()	{
		$this->url->setProtocol ('https');
		$this->assertEqual ($this->url->render (), 'https://forums.devnetwork.net/page.php');
	}

}