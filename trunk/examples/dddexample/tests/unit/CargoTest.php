<?php

include ('Model/Cargo.php');

class CargoTest extends UnitTestCase	{

function __construct() {
	$this->UnitTestCase();
}

function setUp()	{
	$this->trackingId = 1;
	$this->origin = 'origin';
	$this->destination = 'destination'; 
	$this->cargo = new Cargo ($this->trackingId, $this->origin, $this->destination);
}

function testNullTrackingIdThrowsException()	{
	$this->expectException();
	$cargo = new Cargo ($trackingId, $this->origin, $this->destination);
}

function testNullOriginThrowsException()	{
	$this->expectException();
	$cargo = new Cargo ($this->trackingId, $origin, $this->destination);
}

function testNullDestinationThrowsException()	{
	$this->expectException();
	$cargo = new Cargo ($this->trackingId, $this->origin, $destination);
}

function testSetTrackingId()	{
	$this->assertEqual ($this->cargo->trackingId(), $this->trackingId);
}

function testSetOrigin()	{
	$this->assertEqual ($this->cargo->origin(), $this->origin);
}

function testSetDestination()	{
	$this->assertEqual ($this->cargo->destination(), $this->destination);
}

}