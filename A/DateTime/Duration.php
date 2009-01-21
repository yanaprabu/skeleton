<?php
/**
 * Date & Time Duration functionality 
 *
 *
 * @package A_Datetime
 */

class A_DateTime_Duration {

protected $years;
protected $months;
protected $weeks;
protected $days;
protected $hours;
protected $minutes;
protected $seconds;

function construct ($years = 0, $months = 0, $weeks = 0, $days = 0, $hours = 0, $minutes = 0, $seconds = 0)	{
	$this->years = $years;
	$this->months = $months;
	$this->weeks = $weeks;
	$this->days = $days;
	$this->hours = $hours;
	$this->minutes = $minutes;
	$this->seconds = $seconds;
}

function toString()	{
	return $this->years . ' years' . $this->months . ' months' . $this->weeks . ' weeks' . $this->days . ' days' . $this->hours . ' hours' . $this->minutes . ' minutes' . $this->seconds . ' seconds';  
}

function __toString()	{
	return $this->toString();
}

}