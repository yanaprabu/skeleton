<?php

require_once('A/DateTime.php');
require_once('A/DateTime/Range.php');
require_once('A/DateTime/Duration.php');

Mock::Generate ('A_DateTime');
Mock::Generate ('A_DateTime_Duration');

class RangeTest extends UnitTestCase	{

	function setUp()	{
		$this->start = new MockA_DateTime();
		$this->start->setReturnValue ('getTimestamp', 946702800);
		$this->end = new MockA_DateTime();
		$this->end->setReturnValue ('getTimestamp', 1230786000);
		$this->duration = new MockA_DateTime_Duration();
		$this->range = new A_DateTime_Range ($this->start, $this->end);
	}

	function testConstructFromStartAndEnd()	{
		$range = new A_DateTime_Range ($this->start, $this->end);
		$this->assertEqual ($range->getStart(), $this->start);
		$this->assertEqual ($range->getEnd(), $this->end);
	}

	function testConstructFromStartAndDuration()	{
		$this->start->setReturnValue ('add', $this->end);
		$this->start->expectOnce ('add', array ($this->duration));
		$range = new A_DateTime_Range ($this->start, $this->duration);
		$this->assertEqual ($range->getEnd(), $this->end);
	}

	function testConstructFromDurationAndEnd()	{
		$this->end->setReturnValue ('remove', $this->end);
		$this->end->expectOnce ('remove', array ($this->duration));
		$range = new A_DateTime_Range ($this->duration, $this->end);
	}

	function testGetStartWithFormatDelegatesToStart()	{
		$this->start->expectOnce ('format');
		$this->range->getStart ('Y-m-d');
	}
	
	function testGetEndWithFormatDelegatesToEnd()	{
		$this->end->expectOnce ('format');
		$this->range->getEnd ('Y-m-d');
	}
	
}