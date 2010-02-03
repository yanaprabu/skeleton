<?php

Mock::Generate ('A_DateTime');
Mock::Generate ('A_DateTime_Duration');

class RangeTest extends UnitTestCase	{

	function setUp()	{
		$this->start = new MockA_DateTime();
		$this->start->setReturnValue ('getTimestamp', strtotime ('1/1/07'));
		$this->end = new MockA_DateTime();
		$this->end->setReturnValue ('getTimestamp', strtotime ('1/1/09'));
		$this->duration = new MockA_DateTime_Duration();
		$this->duration->setReturnValue ('toString', '+1 year');
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
	
	function testToArray()	{
		$date4 = new MockA_DateTime();
		$date4->setReturnValue ('getTimestamp', strtotime ('1/1/10'));
		
		$date3 = new MockA_DateTime();
		$date3->setReturnValue ('getTimestamp', strtotime ('1/1/09'));
		$date3->setReturnValue ('newModify', $date4);
		
		$date2 = new MockA_DateTime();
		$date2->setReturnValue ('getTimestamp', strtotime ('1/1/08'));
		$date2->setReturnValue ('newModify', $date3);
		
		$date1 = new MockA_DateTime();
		$date1->setReturnValue ('getTimestamp', strtotime ('1/1/07'));
		$date1->setReturnValue ('newModify', $date2);
		
		$this->start->setReturnValue ('newModify', $date1);
		$this->assertEqual ($this->range->toArray ($this->duration), array ($date1, $date2, $date3));
	}
	
}