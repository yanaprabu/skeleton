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
	
	function testContains()	{
		$datetime1 = new A_DateTime();
		$datetime1->parseDate('2001/01/01');
		$datetime2 = new A_DateTime();
		$datetime2->parseDate('2001/02/01');
		$datetime3 = new A_DateTime();
		$datetime3->parseDate('2001/03/01');
		$datetime4 = new A_DateTime();
		$datetime4->parseDate('2001/04/01');
		
		$range = new A_DateTime_Range($datetime1, $datetime3);
		$this->assertTrue($range->contains($datetime2));

		$this->assertFalse($range->contains($datetime4));

		$this->assertFalse($range->contains($datetime1));
		$this->assertTrue($range->contains($datetime1, true));		// inclusive check of end dates

		$this->assertFalse($range->contains($datetime3));
		$this->assertTrue($range->contains($datetime3, true));		// inclusive check of end dates
	}

	function testIntersects()	{
		$datetime1 = new A_DateTime();
		$datetime1->parseDate('2001/01/01');
		$datetime2 = new A_DateTime();
		$datetime2->parseDate('2001/02/01');
		$datetime3 = new A_DateTime();
		$datetime3->parseDate('2001/03/01');
		$datetime4 = new A_DateTime();
		$datetime4->parseDate('2001/04/01');
		$datetime5 = new A_DateTime();
		$datetime5->parseDate('2001/05/01');
		
		// not overlapping
		$range1 = new A_DateTime_Range($datetime1, $datetime2);
		$range2 = new A_DateTime_Range($datetime3, $datetime4);
		$this->assertFalse($range1->intersects($range2));
		
		// range1 end date in range2
		$range1 = new A_DateTime_Range($datetime1, $datetime3);
		$range2 = new A_DateTime_Range($datetime2, $datetime4);
		$this->assertTrue($range1->intersects($range2));
		
		// range1 start date in range2
		$range1 = new A_DateTime_Range($datetime3, $datetime5);
		$range2 = new A_DateTime_Range($datetime1, $datetime4);
		$this->assertTrue($range1->intersects($range2));
		
		// range1 the same as range2
		$range1 = new A_DateTime_Range($datetime2, $datetime4);
		$range2 = new A_DateTime_Range($datetime2, $datetime4);
		$this->assertTrue($range1->intersects($range2));
		
		// range1 end date the same as range2 start date
		$range1 = new A_DateTime_Range($datetime1, $datetime3);
		$range2 = new A_DateTime_Range($datetime3, $datetime5);
		$this->assertTrue($range1->intersects($range2));
		
		// range1 start date the same as range2 end date
		$range1 = new A_DateTime_Range($datetime3, $datetime5);
		$range2 = new A_DateTime_Range($datetime1, $datetime3);
		$this->assertTrue($range1->intersects($range2));
		
		// range1 inside range2
		$range1 = new A_DateTime_Range($datetime2, $datetime4);
		$range2 = new A_DateTime_Range($datetime1, $datetime5);
		$this->assertTrue($range1->intersects($range2));
		
		// range2 inside range1
		$range1 = new A_DateTime_Range($datetime1, $datetime5);
		$range2 = new A_DateTime_Range($datetime2, $datetime4);
		$this->assertTrue($range1->intersects($range2));
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