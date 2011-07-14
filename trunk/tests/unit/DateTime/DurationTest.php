<?php

class DurationTest extends UnitTestCase	{

	function setUp()	{
		$this->duration = new A_Datetime_Duration();
		$this->expectedArray = array(
			'years' => 2,
			'months' => 3,
			'weeks' => 0,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3
		);
		$this->expectedArray2 = array(
			'years' => 0,
			'months' => 0,
			'weeks' => 3,
			'days' => 0,
			'hours' => 0,
			'minutes' => 0,
			'seconds' => 0
		);
		
	}

	function testConstructFromIntegers()	{
		$duration = new A_Datetime_Duration (2,3,0,5,1,10,3);
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
	}
	
	function testConstructconfig()	{
		$duration = new A_Datetime_Duration (array (
			'years' => 2,
			'months' => 3,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3			
		));
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
	}
	
	function testConstructparseDuration()	{
		$duration = new A_Datetime_Duration ('2 years, 3 months, 5 days, 1 hour, 10 minutes, 3 seconds');
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
	}
	
	function testParseDuration()	{
		$this -> duration -> parseDuration ('2 years, 3 months, 5 days, 1 hour, 10 minutes, 3 seconds');
		$this -> assertEqual ($this->duration->toArray(), $this->expectedArray);
	}

	function testConfig()	{
		$this->duration->config (array (
			'years' => 2,
			'months' => 3,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3			
		));
		$this->assertEqual ($this->duration->toArray(), $this->expectedArray);		
	}
	
	function testParseDurationOverwritesExistingValues()	{
		$duration = new A_Datetime_Duration ('4 years, 4 months, 4 weeks, 4 days, 4 hours, 4 minutes, 4 seconds');
		$duration->parseDuration ('2 years, 3 months, 5 days, 1 hour, 10 minutes, 3 seconds');
		$this->assertEqual ($duration->toArray(), $this->expectedArray);
		$duration2 = new A_Datetime_Duration ('4 years, 4 months, 4 weeks, 4 days, 4 hours, 4 minutes, 4 seconds');
		$duration2->parseDuration ('3 weeks');
		$this->assertEqual ($duration2->toArray(), $this->expectedArray2);
	}
	
}