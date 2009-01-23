<?php
/**
 * Date & Time Range functionality 
 *
 *
 * @package A_Datetime
 */

class A_DateTime_Range {

	protected $before;
	protected $after;

	/*
	 * Range spec parameter is in ISO 8601 Time Intervals format
	 * See http://en.wikipedia.org/wiki/ISO_8601#Time_intervals
	 * Can instantiate using start & duration, duration & end, start & end 
	 */
	public function __construct ($first, $second) {
		if ($first instanceof A_DateTime && $second instanceof A_DateTime)	{
			$this->start = $first;
			$this->end = $second;
		}
		if ($first instanceof A_DateTime && $second instanceof A_DateTime_Duration)	{
			$this->start = $first;
			$this->end = $this->start->add ($second);
		}
		if ($first instanceof A_DateTime_Duration && $second instanceof A_DateTime)	{
			$this->start = $second->remove ($first);	
			$this->end = $second;
		}
	}
	
	public function getStart ($format = null) { // what is the appropriate default value for $format? -Cory
		return $this->start;
	}
	
	public function getEnd ($format = null) {
		return $this->end;
	}
	
	public function toArray ($duration)	{
		
	}
	
	public function contains ($datetime, $inclusive = false)	{
		if ($inclusive)	{
			return $datetime->getTimestamp() >= $this->before->getTimestamp() && $datetime->getTimestamp() <= $this->after->getTimestamp();
		} else {
			return $datetime->getTimestamp() > $this->before->getTimestamp() && $datetime->getTimestamp() < $this->after->getTimestamp();
		}
	}
	
}