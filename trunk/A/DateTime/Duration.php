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
protected $positive = true;

function construct ($years = 0, $months = 0, $weeks = 0, $days = 0, $hours = 0, $minutes = 0, $seconds = 0)	{
	$this->years = $years;
	$this->months = $months;
	$this->weeks = $weeks;
	$this->days = $days;
	$this->hours = $hours;
	$this->minutes = $minutes;
	$this->seconds = $seconds;
}

function setPositive()	{
	$this->positive = true;
}

function setNegative()	{
	$this->positive = false;
}

function toString()	{
	$string = array();
	$string[] = $this->buildString ('years', $this->years);
	$string[] = $this->buildString ('months', $this->months);
	$string[] = $this->buildString ('weeks', $this->weeks);
	$string[] = $this->buildString ('days', $this->days);
	$string[] = $this->buildString ('hours', $this->hours);
	$string[] = $this->buildString ('minutes', $this->minutes);
	$string[] = $this->buildString ('seconds', $this->seconds);
	return $string;  
}

function buildString ($key, $value)	{
	return ($this->positive ? $value : (0 - $value)) . ' ' . $key;
}

function __toString()	{
	return $this->toString();
}

}