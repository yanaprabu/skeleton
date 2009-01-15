<?php
require_once('A/Datetime.php');

class DatetimeTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDatetime_getYmd() {
  		$datetime = new A_Datetime();

		$str = $datetime->getYmd();
		$this->assertTrue($str == date('Y-m-d'));

		$str = $datetime->getYmd(true);
		$this->assertTrue($str == date('Y-m-d H:i:s'));
	}
	
	function testDatetime_parseDate() {
  		$datetime = new A_Datetime();

		$datetime->parseDate('2001/12/20');
		$this->assertTrue($datetime->getYmd() == '2001-12-20');

		$datetime->parseDate('12//20//2001');
		$this->assertTrue($datetime->getYmd() == '2001-12-20');

		$datetime->parseDate('20.12.2001');
		$this->assertTrue($datetime->getYmd() == '2001-12-20');

		$datetime->parseDate('12/20/01');
		$this->assertTrue($datetime->getYmd() == '2001-12-20');

		$datetime->parseDate('12/20/01');
		$this->assertTrue($datetime->getYmd() == '2001-12-20');

		$datetime->parseDate('1/2/01');
		$this->assertTrue($datetime->getYmd() == '2001-02-01');

		$datetime->setDayMonthOrder(false);
		$datetime->parseDate('1/2/01');
		$this->assertTrue($datetime->getYmd() == '2001-01-02');

	}
	
	function testDatetime_toString() {
  		$datetime = new A_Datetime();

  		$datetime->parseDate('2001/12/20 12:11:10');
		// default format is 'U' for timestamp so check
		$this->assertTrue("$datetime" == mktime(12, 11, 10, 12, 20, 2001));
 		$datetime->setFormat('d.m.Y');
		$this->assertTrue("$datetime" == '20.12.2001');
		
	}
	
}
