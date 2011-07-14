<?php

class TimezoneTest extends UnitTestCase	{

	function setUp()	{
	}

	function testConstructFromNull() {
		$timezone = new A_Datetime_Timezone();
		$this->assertEqual($timezone->getName(), date_default_timezone_get());
	}

	function testConstructFromTimezoneName() {
		$timezone = new A_Datetime_Timezone('America/New_York');
		$this->assertEqual($timezone->getName(), 'America/New_York');
	}

	function testSetTargetWithTimeZoneName() {
		$timezone = new A_Datetime_Timezone('America/New_York');
		$timezone->setTargetName('America/Los_Angeles');
		$this->assertEqual($timezone->getTargetName(), 'America/Los_Angeles');
	}

	function testGetOffsets() {
		$timezone = new A_Datetime_Timezone('America/New_York');
		$timezone->setTargetName('America/Los_Angeles');
		
		$offset = $timezone->getTargetOffset();
		
		$this->assertEqual($timezone->getOffset(), $offset+3);
	}

	function testGetDifference() {
		$timezone = new A_Datetime_Timezone('America/New_York');
		$timezone->setTargetName('America/Los_Angeles');
		$this->assertEqual($timezone->getDifference(), -3);
	}

}