<?php

Mock::Generate ('Template');
Mock::Generate ('Paginator');
Mock::Generate ('Collection');

class PaginationHelperTest extends UnitTestCase	{

function __construct() {
	$this->UnitTestCase();
}

function setUp()	{
	$this->template = new MockTemplate();
	$this->paginator = new MockPaginator();
	$this->paginator->setReturnValue ('previous', 3);
	$this->paginator->setReturnValue ('next', 5);
	$this->paginator->setReturnValue ('first', 1);
	$this->paginator->setReturnValue ('last', 10);
	$this->paginator->setReturnValue ('page', 4);
	$this->paginator->setReturnValue ('next', 1);
	$this->helper = new PaginationHelper ($this->paginator, $this->template, 3);
}

function testPreviousDelegatesToPaginator()	{
	$this->assertEqual ($this->helper->previous(), 3);
}

function testNextDelegatesToPaginator()	{
	$this->assertEqual ($this->helper->next(), 5);
}

function testFirstDelegatesToPaginator()	{
	$this->assertEqual ($this->helper->first(), 1);
}

function testLastDelegatesToPaginator()	{
	$this->assertEqual ($this->helper->last(), 10);
}

function testPageDelegatesToPaginator()	{
	$this->assertEqual ($this->helper->page(), 4);
}

function testBeforeEqualsSize()	{
	$this->paginator->setReturnValue ('previous', 1);
	$this->assertEqual ($this->helper->before()->count(), 4);
}

function testAfterEqualsSize()	{
	$this->paginator->setReturnValue ('next', 1);
	$this->assertEqual ($this->helper->after()->count(), 4);
}

function testRender()	{
	/*
	 * Brittle tests..
	 * 
	$this->template->expectAt (0, 'set', array ('previous', 3));
	$this->template->expectAt (1, 'set', array ('next', 5));
	$this->template->expectAt (2, 'set', array ('first', 1));
	$this->template->expectAt (3, 'set', array ('last', 10));
	$this->template->expectAt (4, 'set', array ('page', 4));
	$this->template->expectAt (5, 'set', array ('before', '*'));
	$this->template->expectAt (6, 'set', array ('after', '*'));
	*/
	$this->template->expectOnce ('render');
	$this->helper->render();
}

}