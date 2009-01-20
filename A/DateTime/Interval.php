<?php
/**
 * Date & Time Interval functionality 
 *
 *
 * @package A_Datetime
 */

class A_DateTime_Interval extends DateInterval {

	/*
	 * Iterval spec parameter is in ISO 8601 Time Intervals format
	 * See http://en.wikipedia.org/wiki/ISO_8601#Time_intervals
	 */
	public function __construct($interval_spec) {
		parent::__construct($interval_spec);
	}
	
	public function getTimestampBefore() {
	
	}
	
	public function getTimestampAfter() {
	
	}
	
}