<?php

require_once('A/DateTime.php');
require_once('A/DateTime/Range.php');
require_once('A/DateTime/Duration.php');


class RangeTest extends UnitTestCase	{

	function setUp()	{
		$this->duration = new A_DateTime_Duration();
	}

	function testFromString()	{
		$string = '2 years, 3 months, 5 days, 1 hour, 10 minutes, 3 seconds';
		$expectedArray = array(
			'years' => 2,
			'months' => 3,
			'days' => 5,
			'hours' => 1,
			'minutes' => 10,
			'seconds' => 3
		);
		$parts = $this -> duration -> fromString($string);
		$this -> assertEqual($expectedArray,$parts);
	}

}