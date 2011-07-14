<?php
/**
 * DateTime.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author Eran Galt, Cory Kaufman, Christopher Thompson, Cliff Ingham
 */

/**
 * A_Datetime
 *
 * Date and time functionality
 */
class A_Datetime extends DateTime
{

	/**
	 * date format returnd by _toString
	 */
	protected $dateFormat = 'Y-m-d H:i:s';
	
	/**
	 * true dd/mm, false mm/dd
	 */
	protected $dayMonthOrder = true;
	
	/**
	* constructor add date array support.
	*
	* @param array $date
	*/
	public function __construct($date=null, $timezone=null)
	{
		if (is_array($date)) {
			$date = $this->arrayToString($date);
		}
		if ($timezone) {
			parent::__construct($date, $timezone);
		} else {
			parent::__construct($date);
		}
	}
	
	/**
	 * provide fluent interface for DateTime::modify()
	 */
	public function modify($date)
	{
		if (is_array($date)) {
			$date = $this->arrayToString($date);
		}
		parent::modify($date);
		return $this;
	}
	
	/**
	 * provide fluent interface for DateTime::setTimezone()
	 */
	public function setTimezone(DateTimeZone $timezone)
	{
		parent::setTimezone($timezone);
		return $this;
	}
	
	/**
	 * provide fluent interface for DateTime::setTime()
	 */
	public function setTime($hour, $minute, $second=null)
	{
		parent::setTime($hour, $minute, $second);
		return $this;
	}
	
	/**
	 * provide fluent interface for DateTime::setDate()
	 */
	public function setDate($year, $month, $day=null)
	{
		parent::setDate($year, $month, $day);
		return $this;
	}
	
	/**
	 * provide fluent interface for DateTime::setISODate()
	 */
	public function setISODate($year, $week, $day=null)
	{
		parent::setISODate($year, $week, $day);
		return $this;
	}
	
	/**
	 * Set format of value returned by __toString()
	 */
	public function setFormat($format)
	{
		$this->dateFormat = $format;
		return $this;
	}
	
	/**
	 * Set internal format for __tostring
	 */
	public function setDayMonthOrder($dayfirst=true)
	{
		$this->dayMonthOrder = $dayfirst;
		return $this;
	}
	
	/**
	 * Clearer name for existing method
	 */
	public function getDstOffset()
	{
		return $this->getOffset();
	}
	
	/**
	 * get date string in YYYY-MM-DD format
	 */
	public function getDate($time=false)
	{
		return $this->format('Y-m-d' . ($time ? ' H:i:s' : ''));
	}
	
	/**
	 * get timestamp
	 */
	public function getTimestamp()
	{
		return $this->format('U');
	}
	
	/**
	 * get time string in 23:15 or 11:15 pm format, with or without seconds
	 */
	public function getTime($meridian=false, $seconds=false)
	{
		$sec = $seconds ? ':s' : '';
		return $this->format($meridian ? "g:i$sec a" : "G:i$sec");
	}
	
	/**
	 * get year
	 */
	public function getYear()
	{
		return $this->format('Y');
	}
	
	/**
	 * get month
	 */
	public function getMonth()
	{
		return $this->format('n');
	}
	
	/**
	 * get day
	 */
	public function getDay()
	{
		return $this->format('j');
	}
	
	/**
	 * get hour
	 */
	public function getHour($meridian=false)
	{
		return $this->format($meridian ? 'h' : 'H');
	}
	
	/**
	 * get minute
	 */
	public function getMinute()
	{
		return $this->format('i');
	}
	
	/**
	 * get second
	 */
	public function getSecond()
	{
		return $this->format('s');
	}
	
	/**
	 * return a new modified object based on the format string 
	 */
	public function newModify($format)
	{
		$date = clone $this;
		$date->modify($format);
		return $date;
	}
	
	/**
	 * Set date from dates in dd-mm- or mm-dd order, with 2 or 4 digit years and any non-digit separater
	 *  This pro
	 */
	public function parseDate($date)
	{
		// fix dates in mm-dd-yy or mm/dd/yy format
		$datearray = preg_split('/[^0-9]/', $date, -1, PREG_SPLIT_NO_EMPTY);
		if (count($datearray) >= 3) {
			if (strlen($datearray[0]) == 4) {		// is in yyyy-mm-dd
				$year = $datearray[0];
				$month = $datearray[1];
				$day = $datearray[2];
			} else {
				if (strlen($datearray[2]) == 2) {
					if (intval($datearray[2]) > 50) {
						$year = '19' . $datearray[2];
					} else {
						$year = '20' . $datearray[2];
					}
				} else {
					$year = $datearray[2];
				}
				if (intval($datearray[0]) > 12) {
					$month = $datearray[1];
					$day = $datearray[0];
				} elseif (intval($datearray[1]) > 12) {
					$month = $datearray[0];
					$day = $datearray[1];
				} elseif ($this->dayMonthOrder) {
					$month = $datearray[1];
					$day = $datearray[0];
				} else {
					$month = $datearray[0];
					$day = $datearray[1];
				}
			}
		} else {
			if (strlen($date) == 6) {
				// fix dates in ddmmyy/mmddyy format
				$year = '20' . substr($date, 4, 2);
				if ($this->dayMonthOrder) {
					$month = substr($date, 2, 2);
					$day = substr($date, 0, 2);
				} else {
					$month = substr($date, 0, 2);
					$day = substr($date, 2, 2);
				}
			} else if (strlen($date) == 8) {
				// fix dates in yyyymmdd format
				$year = substr($date, 0, 4);
				$month = substr($date, 4, 2);
				$day = substr($date, 6, 2);
			}
		}
		$this->setDate($year, $month, $day);
		// if time values then set time
		if (count($datearray) == 6) {
			$this->setTime($datearray[3], $datearray[4], $datearray[5]);
		}			
		return $this;
	}
	
	public function add (A_Datetime_Duration $duration)
	{
		$duration->setPositive();
		return $this->newModify($duration->toString());
	}
	
	public function remove (A_Datetime_Duration $duration)
	{
		$duration->setNegative();
		return $this->newModify($duration->toString());
	}
	
	/**
	 * check if date/time of another objects is before the date/time of this object
	 */
	public function isBefore($date, $inclusive=false)
	{
		if ($inclusive) {
			return $this->getTimestamp() <= $date->getTimestamp();
		} else {
			return $this->getTimestamp() < $date->getTimestamp();
		}
	}
	
	/**
	 * check if date/time of another objects is before the date/time of this object
	 */
	public function isBeforeOrEqual($date)
	{
		return $this->getTimestamp() <= $date->getTimestamp();
	}
	
	/**
	 * check if date/time of another objects is after the date/time of this object
	 */
	public function isAfter($date, $inclusive=false)
	{
		if ($inclusive) {
			return $this->getTimestamp() >= $date->getTimestamp();
		} else {
			return $this->getTimestamp() > $date->getTimestamp();
		}
	}
	
	/**
	 * check if date/time of another objects is after the date/time of this object
	 */
	public function isAfterOrEqual($date)
	{
		return $this->getTimestamp() >= $date->getTimestamp();
	}
	
	/**
	 * check if this date/time a range
	 */
	public function isWithin (A_Datetime_Range $range, $inclusive = false)
	{
		if ($inclusive)	{
			return $this->getTimestamp() >= $range->getStart() && $this->getTimestamp() <= $range->getEnd();
		} else {
			return $this->getTimestamp() > $range->getStart() && $this->getTimestamp() < $range->getEnd();
		}
	}
	
	/**
	 * check if this date/time a range or equal to the end dates
	 */
	public function isWithinOrEqual(A_Datetime_Range $range)
	{
		return $this->getTimestamp() >= $range->getStart() && $this->getTimestamp() <= $range->getEnd();
	}
	
	/**
	* Converts date array to string.  Array should be in the form of PHP's getdate() array
	*
	* @param array $date
	*/
	public function arrayToString($date=null)
	{
		if (is_array($date)) {
			if (isset($date['month'])) {
				$date['mon'] = $date['month'];		
			}
			if (isset($date['day'])) {
				$date['mday'] = $date['day'];
			}
			if ($date['year'] && $date['mon'] && $date['mday']) {
				$str = "{$date['year']}-{$date['mon']}-{$date['mday']}";
				if (isset($date['hour'])) {
					$date['hours'] = $date['hour'];	 		
				}
				if (isset($date['minute'])) {
					$date['minutes'] = $date['minute'];	 		
				}
				if (isset($date['second'])) {
					$date['seconds'] = $date['second'];	 		
				}
				if (isset($date['hours']) || isset($date['minutes']) || isset($date['seconds'])) {
					$str .= (isset($date['hours']) && $date['hours']) ? " {$date['hours']}:" : ' 00:';
					$str .= (isset($date['minutes']) && $date['minutes']) ? "{$date['minutes']}:" : '00:';
					$str .= (isset($date['seconds']) && $date['seconds']) ? $date['seconds'] : '00';
	 
				}
				return $str;
			}
		}
		return '';
	}
	
	/**
	 * get date using internal format value
	 */
	public function toString()
	{
		return $this->format($this->dateFormat);
	}
	
	/**
	 * get date in in string context using internal format value
	 */
	public function __toString()
	{
		return $this->toString();
	}

}
