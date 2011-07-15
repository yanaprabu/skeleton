<?php
/**
 * Duration.php
 *
 * @package  A_Datetime
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Eran Galt, Cory Kaufman, Christopher Thompson
 */

/**
 * A_Datetime_Duration
 * 
 * Date & Time Duration functionality 
 */
class A_Datetime_Duration
{

	protected $partNames = array('years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds', 'positive');
	protected $years = 0;
	protected $months = 0;
	protected $weeks = 0;
	protected $days = 0;
	protected $hours = 0;
	protected $minutes = 0;
	protected $seconds = 0;
	protected $positive = true;
	
	/**
	 * Configuration at instatiation with Duration string, array or indivitual values
	 * 
	 * @param int $years
	 * @param int $months
	 * @param int $weeks
	 * @param int $days
	 * @param int $hours
	 * @param int $minutes
	 * @param int $seconds
	 */
	public function __construct ($years=0, $months=0, $weeks=0, $days=0, $hours=0, $minutes=0, $seconds=0)
	{
		if(is_array($years)) {
			$this->config ($years);
		} elseif (is_string($years)) {
			$this->parseDuration($years);
		} else {
			$this->config(array(
				'years' => $years,
				'months' => $months,
				'weeks' => $weeks,
				'days' => $days,
				'hours' => $hours,
				'minutes' => $minutes,
				'seconds' => $seconds
			));
		}
	}
	
	/**
	 * parse a Duration string in the format "1 years 1 months 3 weeks 4 days 5 hours 6 minutes 7 seconds"
	 * 
	 * @param string $string
	 * @return array
	 */
	public function parseDuration ($string)
	{
		$parts = array();
		$stringParts = explode(',', $string);
		foreach($stringParts as $part) {
			$part = trim($part);
			$breakPos = strpos($part, ' ');
			if($breakPos !== false) {
				$num = (int) substr($part, 0, $breakPos);
				$partName = substr($part, $breakPos + 1);
			} else {
				$num = 1;
				$partName = $part;
			}
			if($partName[strlen($partName) - 1] != 's') {
				$partName .= 's';
			}
			if(in_array($partName,$this->partNames)) {
				$parts[$partName] = $num;
			}
		}
		$this->config($parts);
		return $parts;
	}
	
	/**
	 * Configure class with assoc array. See partNames property in the format array('years'=>1, 'months'=>2, 'weeks'=>3, 'days'=>4, 'hours'=>5, minutes'=>6, 'seconds'=>7, 'positive'=>true).  Values omitted from the array will be set to zero
	 * 
	 * @param array $parts
	 */
	public function config($parts)
	{
		foreach($this->partNames as $part) {
			$this->$part = isset($parts[$part]) ? $parts[$part] : 0;
		}
	}
	
	/**
	 * Set Duration to be used as a positive value when used with a date.
	 */
	public function setPositive()
	{
		$this->positive = true;
	}
	
	/**
	 * Set Duration to be used as a negative value when used with a date 
	 */
	public function setNegative()
	{
		$this->positive = false;
	}
	
	/**
	 * Return Duration string in strtotime() style in the format "1 years 1 months 3 weeks 4 days 5 hours 6 minutes 7 seconds" 
	 */
	public function toString()
	{
		$string = array();
		foreach($this->partNames as $part) {
			$value = $this->$part;
			$string[] = $this->positive ? $value : ((0 - $value) . ' ' . $part);
		}
		return implode(', ', $string);  
	}
	
	/**
	 * Return Duration values in assoc array
	 * 
	 * @return array
	 */
	public function toArray()	{
		return array (
			'years' => $this->years,
			'months' => $this->months,
			'weeks' => $this->weeks,
			'days' => $this->days,
			'hours' => $this->hours,
			'minutes' => $this->minutes,
			'seconds' => $this->seconds
		);
	}
			
	/**
	 * Return value of Duration when used in string context per toString() method
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

}