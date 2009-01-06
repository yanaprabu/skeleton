<?php

class CollectionTest extends UnitTestCase	{

function __construct() {
	$this->UnitTestCase();
}

function testEmptyCollectionReturnsNull()	{
	$collection = new Collection();
	$this->assertEqual ($collection->current(), null);
}

function testCurrentReturnsFirstItem()	{
	$collection = new Collection (array (1));
	$this->assertEqual ($collection->current(), 1);
}

function testKeyReturnsPosition()	{
	$collection = new Collection (array (1));
	$this->assertEqual ($collection->key(), 0);
}

function testNextAdvancesPosition()	{
	$collection = new Collection (array (1, 2));
	$collection->next();
	$this->assertEqual ($collection->key(), 1);
}

function testRewindResetsPosition()	{
	$collection = new Collection (array (1, 2));
	$collection->next();
	$collection->rewind();
	$this->assertEqual ($collection->key(), 0);
}

function testInvalidPositionReturnsFalse()	{
	$collection = new Collection();
	$this->assertFalse ($collection->valid());
}

function testValidPositionReturnsTrue()	{
	$collection = new Collection (array (1));
	$this->assertTrue ($collection->valid());
}

}