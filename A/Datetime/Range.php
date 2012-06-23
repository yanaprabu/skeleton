<?php
/**
 * Range.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Eran Galt, Cory Kaufman, Christopher Thompson
 */

/**
 * A_Datetime_Range
 *
 * Date & Time Range functionality
 *
 * @package A_Datetime
 */
class A_Datetime_Range
{

	protected $start;		// A_Datetime object containing start of range
	protected $end;			// A_Datetime object containing end  of range

	/**
	 * Range spec parameter is in ISO 8601 Time Intervals format
	 * See http://en.wikipedia.org/wiki/ISO_8601#Time_intervals
	 * Can instantiate using start & duration, duration & end, start & end
	 *
	 * @param A_Datetime|A_Datetime_Duration $first Either the start point, or a duration.
	 * @param A_Datetime_Duration|A_Datetime $second Either the end point, or a duration
	 */
	public function __construct($first, $second)
	{
		if ($first instanceof A_Datetime && $second instanceof A_Datetime) {
			$this->start = $first;
			$this->end = $second;
		}
		if ($first instanceof A_Datetime && $second instanceof A_Datetime_Duration) {
			$this->start = $first;
			$this->end = $this->start->add($second);
		}
		if ($first instanceof A_Datetime_Duration && $second instanceof A_Datetime) {
			$this->start = $second->remove($first);
			$this->end = $second;
		}
	}

	/**
	 * Return start date/time as object, or if format given as formatted string
	 *
	 * @param string $format Date format syntax according to the date() function.  Omit or set to null for A_Datetime object.
	 * @return string|A_Datetime
	 * @see date()
	 */
	public function getStart($format=null)
	{
		if ($format) {
			return $this->start->format($format);
		}
		return $this->start;
	}

	/**
	 * Return end date/time as object, or if format given as formatted string.
	 *
	 * @param string $format Date format syntax according to the date() function.  Omit or set to null for A_Datetime object.
	 * @return string|A_Datetime
	 * @see date()
	 */
	public function getEnd($format=null)
	{
		if ($format) {
			return $this->end->format($format);
		}
		return $this->end;
	}

	/**
	 * Return an array of date/time objects from the start to end date using $duration as the interval
	 *
	 * @param A_Datetime_Duration
	 * @return array
	 */
	public function toArray($duration)
	{
		$date = $this->start->newModify();
		$string = $duration instanceof A_Datetime_Duration ? $duration->toString() : $duration;
		$ranges = array();
		while ($date->getTimestamp() <= $this->end->getTimestamp()) {
			$ranges[] = $date;
			$date = $date->newModify ($string);
		}
		return $ranges;
	}

	/**
	 * Checks whether or not the given DateTime object is within this range
	 *
	 * @param A_Datetime $datetime DateTime object to check
	 * @param bool $inclusive Set to true to include start and end in range
	 * @return bool
	 */
	public function contains($datetime, $inclusive=false)
	{
		if ($datetime instanceof A_Datetime) {
			if ($inclusive) {
				return $datetime->getTimestamp() >= $this->start->getTimestamp() && $datetime->getTimestamp() <= $this->end->getTimestamp();
			} else {
				return $datetime->getTimestamp() > $this->start->getTimestamp() && $datetime->getTimestamp() < $this->end->getTimestamp();
			}
		}
	}

	/**
	 * Checks whether or not the given Range object intersects this range
	 *
	 * @param A_Datetime_Range $range Range object to check
	 * @return bool
	 */
	public function intersects($range)
	{
		if (!$range
			|| ($this->end->getTimestamp() < $range->getStart()->getTimestamp())
			|| ($range->getEnd()->getTimestamp() < $this->start->getTimestamp())
			) {
			return false;
		}
		return true;
	}

	/**
	 * Formats Range as a string according to DATE_ISO8601/DATE_ISO8601 standards
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->start->format(DATE_ISO8601) . '/' . $this->end->format(DATE_ISO8601);
	}

	/**
	 * Return value of Range when used in string context per toString() method
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

}
