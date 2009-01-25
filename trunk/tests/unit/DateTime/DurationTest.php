<?php

require_once('A/DateTime.php');
require_once('A/DateTime/Range.php');
require_once('A/DateTime/Duration.php');


class RangeTest extends UnitTestCase	{

	function setUp()	{
		$this->duration = new A_DateTime_Duration();
		$this->expectedArray = array(
			'years' => 2,
			'months' => 3,
			'weeks' => 0,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3
		);
	}

	function testConstructFromIntegers()	{
		$duration = new A_DateTime_Duration (2,3,0,5,1,10,3);
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
	}
	
	function testConstructFromArray()	{
		$duration = new A_DateTime_Duration (array (
			'years' => 2,
			'months' => 3,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3			
		));
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
	}
	
	function testConstructFromString()	{
		$duration = new A_DateTime_Duration ('2 years, 3 months, 5 days, 1 hour, 10 minutes, 3 seconds');
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
	}
	
	function testFromString()	{
		$this -> duration -> fromString ('2 years, 3 months, 5 days, 1 hour, 10 minutes, 3 seconds');
		$this -> assertEqual ($this->duration->toArray(), $this->expectedArray);
	}

	function testFromArray()	{
		$this->duration->fromArray (array (
			'years' => 2,
			'months' => 3,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3			
		));
		$this->assertEqual ($this->duration->toArray(), $this->expectedArray);		
	}
	
}