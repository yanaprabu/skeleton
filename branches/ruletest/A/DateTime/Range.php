<?php
/**
 * Range.php
 *
 * @package  A_DateTime
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Eran Galt, Cory Kaufman, Christopher Thompson
 */

/**
 * A_Datetime_Range
 * 
 * Date & Time Range functionality
 */
class A_Datetime_Range {

	protected $start;		// A_Datetime object containing start of range
	protected $end;			// A_Datetime object containing end  of range

	/*
	 * Range spec parameter is in ISO 8601 Time Intervals format
	 * See http://en.wikipedia.org/wiki/ISO_8601#Time_intervals
	 * Can instantiate using start & duration, duration & end, start & end 
	 */
	public function __construct ($first, $second) {
		if ($first instanceof A_Datetime && $second instanceof A_Datetime)	{
			$this->start = $first;
			$this->end = $second;
		}
		if ($first instanceof A_Datetime && $second instanceof A_Datetime_Duration)	{
			$this->start = $first;
			$this->end = $this->start->add ($second);
		}
		if ($first instanceof A_Datetime_Duration && $second instanceof A_Datetime)	{
			$this->start = $second->remove ($first);	
			$this->end = $second;
		}
	}
	
	/*
	 * Return start date/time as object, or if format given as formatted string
	 */
	public function getStart ($format = null) {
		if ($format)	{
			return $this->start->format ($format);
		}
		return $this->start;
	}
	
	/*
	 * Return end date/time as object, or if format given as formatted string
	 */
	public function getEnd ($format = null) {
		if ($format)	{
			return $this->end->format ($format);
		}
		return $this->end;
	}
	
	/*
	 * Return an array of date/time objects from the start to end date using $duration as the interval
	 */
	public function toArray ($duration)	{
		$date = $this->start->newModify();
		$string = $duration instanceof A_Datetime_Duration ? $duration->toString() : $duration;
		$ranges = array();
		while ($date->getTimestamp() <= $this->end->getTimestamp())	{
			$ranges[] = $date;
			$date = $date->newModify ($string);
		}
		return $ranges;
	}
	
	/*
	 * Return true|false whether a given date/time object is withiin this range
	 * The inclusive parameter determines whether the start and end dates are included in the Range
	 */
	public function contains ($datetime, $inclusive = false)	{
		if ($datetime instanceof A_Datetime) {
			if ($inclusive)	{
				return $datetime->getTimestamp() >= $this->start->getTimestamp() && $datetime->getTimestamp() <= $this->end->getTimestamp();
			} else {
				return $datetime->getTimestamp() > $this->start->getTimestamp() && $datetime->getTimestamp() < $this->end->getTimestamp();
			}
		}
	}
	
/*
	 * Return true|false whether a given Range object intersects with the current one
	 */
	public function intersects ($range)	{
		if (
				!$range 
				|| ($this->end->getTimestamp() < $range->getStart()->getTimestamp()) 
				|| ($range->getEnd()->getTimestamp() < $this->start->getTimestamp())
			) {
			return false;
		}

		return true;
	}

	/*
	 * Return Range string in strtotime() style
	 * format: DATE_ISO8601/DATE_ISO8601 
	 */
	public function toString()	{
		return $this->start->format(DATE_ISO8601) . '/' . $this->end->format(DATE_ISO8601);  
	}
	

		/*
	 * Return value of Range when used in string context per toString() method
	 */
	public function __toString() {
		return $this->toString();
	}

}