<?php
/**
 * Date & time functionality 
 *
DateTime class methods:
DateTime::__construct  ([ string $time  [, DateTimeZone $timezone  ]] )
DateTime::format  ( string $format  ) // Returns date formatted according to given format (same as accepted by date()).
DateTime::modify  ( string $modify  ) // Alter the timestamp of a DateTime object by incrementing or decrementing in a format accepted by strtotime().
DateTime::getTimezone  ( void  ) // Return time zone relative to given DateTime
DateTime::setTimezone  ( DateTimeZone $timezone  )
DateTime::getOffset  ( void  ) // Returns the daylight saving time offset
DateTime::setTime($hour, $minute, $second=null) // sets the current time of the DateTime object to a different time.
DateTime::setDate($year  , int $month  , int $day  ) // sets the current date of the DateTime object to a different date.
DateTime::setISODate($year, $week, $day=null) // Set a date according to the ISO 8601 standard - using weeks and day offsets rather than specific dates.

DateTimeZone class methods:
DateTimeZone::__construct  ( string $timezone  )
DateTimeZone::getName()
DateTimeZone::getOffset()
DateTimeZone::getTransitions()
DateTimeZone::listAbbreviations()
DateTimeZone::listIdentifiers()

date_sub  ( DateTime $object  , DateRange $range  )

Ideas: http://laughingmeme.org/2007/02/27/
 *
 * @package A_Datetime
 */

class A_DateTime extends DateTime {
	/**
	 * date format returnd by _toString
	 */
	protected $dateFormat = 'Y-m-d H:i:s';
	/**
	 * true dd/mm, false mm/dd
	 */
	protected $dayMonthOrder = true;
	
	/**
	 * provide fluent interface for DateTime::modify()
	 */
	public function modify($modify) {
		parent::modify($modify);
		return $this;
	}

	/**
	 * provide fluent interface for DateTime::setTimezone()
	 */
	public function setTimezone(DateTimeZone $timezone) {
		parent::setTimezone($timezone);
		return $this;
	}

	/**
	 * provide fluent interface for DateTime::setTime()
	 */
	public function setTime($hour, $minute, $second=null) {
		parent::setTime($hour, $minute, $second);
		return $this;
	}

	/**
	 * provide fluent interface for DateTime::setDate()
	 */
	public function setDate($year, $month, $day=null) {
		parent::setDate($year, $month, $day);
		return $this;
	}

	/**
	 * provide fluent interface for DateTime::setISODate()
	 */
	public function setISODate($year, $week, $day=null) {
		parent::setISODate($year, $week, $day);
		return $this;
	}

	/**
	 * Set format of value returned by __toString()
	 */
	public function setFormat($format) {
		$this->dateFormat = $format;
		return $this;
	}

	/**
	 * Set internal format for __tostring
	 */
	public function setDayMonthOrder($dayfirst=true) {
		$this->dayMonthOrder = $dayfirst;
		return $this;
	}

	/**
	 * Clearer name for existing method
	 */
	public function getDstOffset() {
		return $this->getOffset();
	}

	/**
	 * get date string in YYYY-MM-DD format
	 */
	public function getDate($time=false) {
		return $this->format('Y-m-d' . ($time ? ' H:i:s' : ''));
	}

	/**
	 * get timestamp
	 */
	public function getTimestamp() {
		return $this->format('U');
	}

	/**
	 * get time string in 23:15 or 11:15 pm format, with or without seconds
	 */
	public function getTime($meridian=false, $seconds=false) {
		$sec = $seconds ? ':s' : '';
		return $this->format($meridian ? "g:i$sec a" : "G:i$sec");
	}

	/**
	 * get year
	 */
	public function getYear() {
		return $this->format('Y');
	}

	/**
	 * get month
	 */
	public function getMonth() {
		return $this->format('n');
	}

	/**
	 * get day
	 */
	public function getDay() {
		return $this->format('j');
	}

	/**
	 * get hour
	 */
	public function getHour($meridian=false) {
		return $this->format($meridian ? 'h' : 'H');
	}

	/**
	 * get minute
	 */
	public function getMinute() {
		return $this->format('i');
	}

	/**
	 * get second
	 */
	public function getSecond() {
		return $this->format('s');
	}

	/**
	 * return a new modified object based on the format string 
	 */
	public function newModify($format) {
		$date = clone $this;
		$date->modify($format);
		return $date;
	}

	/**
	 * Set date from dates in dd-mm- or mm-dd order, with 2 or 4 digit years and any non-digit separater
	 *  This pro
	 */
	public function parseDate($date) {
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
/*
		} elseif (strlen($date) == 6) {
			// fix dates in mmddyy format
			$year = '20' . substr($date, 4, 2);
			$month = substr($date, 0, 2);
			$day = substr($date, 2, 2);
*/
		}
		$this->setDate($year, $month, $day);
		// if time values then set time
		if (count($datearray) == 6) {
			$this->setTime($datearray[3], $datearray[4], $datearray[5]);
		}			
		return $this;
	}

	public function add (A_DateTime_Duration $duration)	{
		return $this->newModify ($duration->toString());
	}
	
	public function remove (A_DateTime_Duration $duration)	{
	}
	
	/**
	 * check if date/time of another objects is before the date/time of this object
	 */
	public function isBefore($date, $inclusive=false)	{
		if ($inclusive) {
			return $this->getTimestamp() <= $date->getTimestamp();
		} else {
			return $this->getTimestamp() < $date->getTimestamp();
		}
	}
	
	/**
	 * check if date/time of another objects is after the date/time of this object
	 */
	public function isAfter($date, $inclusive=false)	{
		if ($inclusive) {
			return $this->getTimestamp() >= $date->getTimestamp();
		} else {
			return $this->getTimestamp() > $date->getTimestamp();
		}
	}

	public function isWithin (A_DateTime_Range $range, $inclusive = false)	{
		if ($inclusive)	{
			return $this->getTimestamp() >= $range->getStart() && $this->getTimestamp() <= $range->getEnd();
		} else {
			return $this->getTimestamp() > $range->getStart() && $this->getTimestamp() < $range->getEnd();
		}
	}
	
	/**
	 * get date using internal format value
	 */
	public function toString()	{
		return $this->format($this->dateFormat);
	}
	
	/**
	 * get date in in string context using internal format value
	 */
	public function __toString() {
		return $this->toString();
	}

}


/*

 */