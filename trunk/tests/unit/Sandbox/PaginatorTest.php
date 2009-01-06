<?php

Mock::Generate ('Collection');

class PaginatorTest extends UnitTestCase	{

function __construct() {
	$this->UnitTestCase();
}

function setUp()	{
	$this->collection = new MockCollection();
	$this->collection->setReturnValue ('count', 20);
	$this->paginator = new Paginator ($this->collection, 4, 3);
}

function testCurrent()	{
	$this->collection->expectOnce ('slice', array (9, 3));
	$this->paginator->current();
}

function testCount()	{
	$this->collection->expectOnce ('count');
	$this->paginator->count();
}

function testfirst()	{
	$this->assertEqual ($this->paginator->first(), 1);
}

function testLast()	{
	$this->assertEqual ($this->paginator->last(), 7);
}

function testPreviousReturnsTrueWhenPreviousExists()	{
	$this->assertTrue ($this->paginator->previous());
}

function testNoPreviousReturnsFalse()	{
	$this->assertFalse ($this->paginator->previous (4));
}

function testNextReturnsTrueWhenNextExists()	{
	$this->assertTrue ($this->paginator->next());
}

function testNoNextReturnsFalse()	{
	$this->assertFalse ($this->paginator->next (4));
}

}