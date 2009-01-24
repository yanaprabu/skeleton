<?php
require_once('A/DateTime.php');
require_once('A/DateTime/Range.php');
require_once('A/DateTime/Duration.php');

Mock::Generate ('A_DateTime_Range');
Mock::Generate ('A_DateTime_Duration');

class DatetimeTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDatetime_parseDate() {
  		$datetime = new A_DateTime();

		$datetime->parseDate('2001/12/20');
		$this->assertTrue($datetime->getDate() == '2001-12-20');

		$datetime->parseDate('12//20//2001');
		$this->assertTrue($datetime->getDate() == '2001-12-20');

		$datetime->parseDate('20.12.2001');
		$this->assertTrue($datetime->getDate() == '2001-12-20');

		$datetime->parseDate('12/20/01');
		$this->assertTrue($datetime->getDate() == '2001-12-20');

		$datetime->parseDate('12/20/01');
		$this->assertTrue($datetime->getDate() == '2001-12-20');

		$datetime->parseDate('1/2/01');
		$this->assertTrue($datetime->getDate() == '2001-02-01');

		$datetime->setDayMonthOrder(false);
		$datetime->parseDate('1/2/01');
		$this->assertTrue($datetime->getDate() == '2001-01-02');

	}
	
	function testDatetime_toString() {
  		$datetime = new A_Datetime();

  		$datetime->parseDate('2001/12/20 12:11:10');
		// default format is 'U' for timestamp so check
		$this->assertTrue("$datetime" == '2001-12-20 12:11:10');
 		$datetime->setFormat('d.m.Y');
		$this->assertTrue("$datetime" == '20.12.2001');
		
	}
	
	function testDatetime_Getters() {
  		$datetime = new A_Datetime();

  		$datetime->parseDate('2008/12/20 21:11:10');
		// default format is 'U' for timestamp so check
		$this->assertTrue($datetime->getYear() == 2008);
		$this->assertTrue($datetime->getMonth() == 12);
		$this->assertTrue($datetime->getDay() == 20);
		$this->assertTrue($datetime->getHour() == 21);
		$this->assertTrue($datetime->getMinute() == 11);
		$this->assertTrue($datetime->getSecond() == 10);
		
	}
	
	function testDatetime_getDate() {
  		$datetime = new A_Datetime();
  		$datetime->parseDate('2008-12-20 21:11:10');
  		
		$str = $datetime->getDate();
		$this->assertTrue($str == '2008-12-20');

		$str = $datetime->getDate(true);
		$this->assertTrue($str == '2008-12-20 21:11:10');
	}
	
	function testDatetime_getTime() {
  		$datetime = new A_Datetime();

		$str = $datetime->getTime();
		$this->assertTrue($str == date('H:i'));

		$str = $datetime->getTime(false, true);
		$this->assertTrue($str == date('H:i:s'));

		$str = $datetime->getTime(true);
		$this->assertTrue($str == date('g:i a'));

		$str = $datetime->getTime(true, true);
		$this->assertTrue($str == date('g:i:s a'));
	}
	
	function testDatetime_BeforeAfter() {
  		$date1 = new A_Datetime();
 		$date1->parseDate('2008/12/20 21:11:10');

  		$date2 = new A_Datetime();
 		$date2->parseDate('2008/12/21');
		$this->assertTrue($date1->isBefore($date2));

  		$date2->parseDate('2008/12/19');
		$this->assertTrue($date1->isAfter($date2));
		
	}

	function testIsWithinRangeReturnsTrue()	{
		$date1 = new A_DateTime();
		$date1->parseDate ('2008/12/22');
		$date2 = new A_DateTime();
		$date2->parseDate ('2008/12/21');
		$date3 = new A_DateTime();
		$date3->parseDate ('2008/12/23');
		$range = new MockA_DateTime_Range();
		$range->setReturnValue ('getStart', $date2->getTimestamp());
		$range->setReturnValue ('getEnd', $date3->getTimestamp());
		$this->assertTrue ($date1->isWithin ($range));
	}

	function testIsNotWithinRangeReturnsFalse()	{
		$date1 = new A_DateTime();
		$date1->parseDate ('2008/12/20');
		$date2 = new A_DateTime();
		$date2->parseDate ('2008/12/21');
		$date3 = new A_DateTime();
		$date3->parseDate ('2008/12/23');
		$range = new MockA_DateTime_Range();
		$range->setReturnValue ('getStart', $date2->getTimestamp());
		$range->setReturnValue ('getEnd', $date3->getTimestamp());
		$this->assertFalse ($date1->isWithin ($range));
	}

	function testAddReturnsNewDate()	{
		$date = new A_DateTime();
		$duration = new MockA_DateTime_Duration();
		$duration->setReturnValue ('toString', '+1 day');
		$duration->expectOnce ('setPositive');
		$duration->expectOnce ('toString');
		$date2 = $date->add ($duration);
		$this->assertEqual ($date2->getTimestamp(), strtotime ('+1 day', $date->getTimestamp()));
	}
	
	function testRemoveReturnsNewDate()	{
		$date = new A_DateTime();
		$duration = new MockA_DateTime_Duration();
		$duration->setReturnValue ('toString', '-1 days');
		$duration->expectOnce ('setNegative');
		$duration->expectOnce ('toString');
		$date2 = $date->remove ($duration);
		$this->assertEqual ($date2->getTimestamp(), strtotime ('-1 day', $date->getTimestamp())); 
	}
	
}