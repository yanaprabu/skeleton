<?php

include ('A/Pagination/Url.php');

class Pagination_UrlTest extends UnitTestCase	{

	public function setUp()	{
		$this->url = new A_Pagination_Url ('forums.devnetwork.net', 'http');
	}

	public function testSingleSetParameter()	{
		$this->url->set('order', 'title');
		$this->assertEqual ($this->url->render ('page.php'), 'http://forums.devnetwork.net/page.php?order=title');
	}

	public function testDoubleSetParameter()	{
		$this->url->set ('order', 'title');
		$this->url->set ('pageSize', '5');
		$this->assertEqual ($this->url->render ('page.php'), 'http://forums.devnetwork.net/page.php?order=title&pageSize=5');
	}

	public function testDoubleSetParameterWithBuildParameter()	{
		$this->url->set ('order', 'title');
		$this->url->set ('pageSize', '5');
		$this->assertEqual ($this->url->render ('page.php', array ('page' => 2)), 'http://forums.devnetwork.net/page.php?page=2&order=title&pageSize=5');
	}

	public function testSetBase()	{
		$this->url->setBase ('www.devnetwork.net');
		$this->assertEqual ($this->url->render ('page.php'), 'http://www.devnetwork.net/page.php');
	}

	public function testSetProtocol()	{
		$this->url->setProtocol ('https');
		$this->assertEqual ($this->url->render ('page.php'), 'https://forums.devnetwork.net/page.php');
	}

}