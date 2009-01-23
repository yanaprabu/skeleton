<?php
/**
 * Date & Time Range functionality 
 *
 *
 * @package A_Datetime
 */

class A_DateTime_Range {

	protected $before; // how are these stored internally? unix timestamps?
	protected $after;

	/*
	 * Range spec parameter is in ISO 8601 Time Intervals format
	 * See http://en.wikipedia.org/wiki/ISO_8601#Time_intervals
	 * Can instantiate using start & duration, duration & end, start & end 
	 */
	public function __construct($range_spec) {

	}
	
	public function getStart ($format) {
	
	}
	
	public function getEnd ($format) {
	
	}
	
	public function toArray ($duration)	{
		
	}
	
	public function contains ($datetime, $inclusive = false)	{
		if ($inclusive)	{
			return $datetime->getTimestamp() >= $this->before && $datetime->getTimestamp() <= $this->after;
		} else {
			return $datetime->getTimestamp() > $this->before && $datetime->getTimestamp() < $this->after;
		}
	}
	
}