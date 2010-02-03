<?php

Mock::Generate ('Collection');

class PaginatorTest extends UnitTestCase	{

function __construct() {
	$this->UnitTestCase();
}

function setUp()	{
	$this->collection = new MockCollection();
	$this->collection->setReturnValue ('count', 20);
	$this->paginator = new Paginator ($this->collection);
	$this->paginator->setPageSize (3);
	$this->paginator->setCurrentPage (4);
}

function testCurrent()	{
	$this->collection->expectOnce ('slice', array (9, 3));
	$this->paginator->current();
}

function testCount()	{
	$this->collection->expectAtLeastOnce ('count');
	$this->paginator->count();
}

function testfirst()	{
	$this->assertEqual ($this->paginator->firstPage(), 1);
}

function testLast()	{
	$this->assertEqual ($this->paginator->lastPage(), 7);
}

function testValidIsWithinBoundsReturnsTrue()	{
	$this->assertTrue ($this->paginator->validPage (3));
}

function testValidIsBelowBoundsReturnsFalse()	{
	$this->assertFalse ($this->paginator->validPage (-1));
}

function testValidIsAboveBoundsReturnsFalse()	{
	$this->assertFalse ($this->paginator->validPage (100));
}

function testPreviousReturnedWhenPreviousExists()	{
	$this->assertEqual ($this->paginator->previous(), $this->paginator->page() - 1);
}

function testNoPreviousReturnsFirst()	{
	$this->assertEqual ($this->paginator->previous (4), $this->paginator->firstPage());
}

function testNextReturnedTrueWhenNextExists()	{
	$this->assertEqual ($this->paginator->next(), $this->paginator->page() + 1);
}

function testNoNextReturnsLast()	{
	$this->assertEqual ($this->paginator->next (4), $this->paginator->lastPage());
}

}