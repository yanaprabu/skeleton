<?php

require_once('A/DateTime.php');
require_once('A/DateTime/Timezone.php');

#Mock::Generate('A_DateTime');
#Mock::Generate('A_DateTime_Duration');

class TimezoneTest extends UnitTestCase	{

	function setUp()	{
	}

	function testConstructFromNull() {
		$timezone = new A_DateTime_Timezone();
		$this->assertEqual($timezone->getName(), date_default_timezone_get());
	}

	function testConstructFromTimezoneName() {
		$timezone = new A_DateTime_Timezone('America/New_York');
		$this->assertEqual($timezone->getName(), 'America/New_York');
	}

	function testSetTargetWithTimeZoneName() {
		$timezone = new A_DateTime_Timezone('America/New_York');
		$timezone->setTargetName('America/Los_Angeles');
		$this->assertEqual($timezone->getTargetName(), 'America/Los_Angeles');
	}

	function testGetOffsets() {
		$timezone = new A_DateTime_Timezone('America/New_York');
		$timezone->setTargetName('America/Los_Angeles');
		$this->assertEqual($timezone->getOffset(), -4);
		$this->assertEqual($timezone->getTargetOffset(), -7);
	}

	function testGetDifference() {
		$timezone = new A_DateTime_Timezone('America/New_York');
		$timezone->setTargetName('America/Los_Angeles');
		$this->assertEqual($timezone->getDifference(), -3);
	}

}