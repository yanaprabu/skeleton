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

date_sub  ( DateTime $object  , DateInterval $interval  )

Ideas: http://laughingmeme.org/2007/02/27/
 *
 * @package A_Datetime
 */

class A_Datetime extends DateTime {
	/**
	 * date format returnd by _toString
	 */
	protected $dateFormat = 'Ymd\THis\Z';
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
		return (int)$this->format('Y');
	}

	/**
	 * get month
	 */
	public function getMonth() {
		return (int)$this->format('n');
	}

	/**
	 * get day
	 */
	public function getDay() {
		return (int)$this->format('j');
	}

	/**
	 * get hour
	 */
	public function getHour($meridian=false) {
		return (int)$this->format($ordinal ? 'h' : 'H');
	}

	/**
	 * get minute
	 */
	public function getMinute() {
		return (int)$this->format('i');
	}

	/**
	 * get second
	 */
	public function getSecond() {
		return (int)$this->format('s');
	}

	/**
	 * Set $dayMonthOrder property
	 */
	public function newModify($format) {
		$date = clone $this;
		$date->modify($format);
		return $date;
	}

	/**
	 * set date from dates in YYYY-MM-DD
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