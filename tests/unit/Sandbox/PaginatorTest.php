<?php

include ('A/Sandbox/Collection.php');
include ('A/Sandbox/Paginator.php');

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

function testValidIsWithinBoundsReturnsTrue()	{
	$this->assertTrue ($this->paginator->valid (3));
}

function testValidIsBelowBoundsReturnsFalse()	{
	$this->assertFalse ($this->paginator->valid (-1));
}

function testValidIsAboveBoundsReturnsFalse()	{
	$this->assertFalse ($this->paginator->valid (100));
}

function testPreviousReturnedWhenPreviousExists()	{
	$this->assertEqual ($this->paginator->previous(), $this->paginator->page() - 1);
}

function testNoPreviousReturnsFirst()	{
	$this->assertEqual ($this->paginator->previous (4), $this->paginator->first());
}

function testNextReturnedTrueWhenNextExists()	{
	$this->assertEqual ($this->paginator->next(), $this->paginator->page() + 1);
}

function testNoNextReturnsLast()	{
	$this->assertEqual ($this->paginator->next (4), $this->paginator->last());
}

}