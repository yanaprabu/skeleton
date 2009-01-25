<?php
/**
 * Date & Time Duration public functionality 
 *
 *
 * @package A_Datetime
 */
	
class A_DateTime_Duration {
	
	protected $_partNames = array('years','months','weeks','days','hours','minutes','seconds');
	protected $years = 0;
	protected $months = 0;
	protected $weeks = 0;
	protected $days = 0;
	protected $hours = 0;
	protected $minutes = 0;
	protected $seconds = 0;
	protected $positive = true;
	
	public function __construct ($years = 0, $months = 0, $weeks = 0, $days = 0, $hours = 0, $minutes = 0, $seconds = 0)	{
		if (func_num_args() == 1) {
			if (is_array ($years)) {
				$this->fromArray ($years);
			} elseif (is_string ($years)) {
				$this->fromString ($years);
			}
		} else	{
			$this->fromArray (array (
				'years' => $years,
				'months' => $months,
				'weeks' => $weeks,
				'days' => $days,
				'hours' => $hours,
				'minutes' => $minutes,
				'seconds' => $seconds)
			);
		}
	}
	
	public function fromString ($string)	{
		$parts = array();
		$stringParts = explode(',',$string);
		foreach($stringParts as $part) {
			$part = trim($part);
			$breakPos = strpos($part,' ');
			if($breakPos !== false) {
				$num = (int) substr($part,0,$breakPos);
				$partName = substr($part,$breakPos + 1);
			} else {
				$num = 1;
				$partName = $part;
			}
			if($partName[strlen($partName) - 1] != 's') {
				$partName .= 's';
			}
			if(in_array($partName,$this -> _partNames)) {
				$parts[$partName] = $num;
			}
		}
		$this->fromArray ($parts);
	}
	
	public function fromArray ($array)	{
		foreach ($array as $key => $value)	{
			if (property_exists ($this, $key)) $this->{$key} = $value;
		}		
	}
	
	public function setPositive()	{
		$this->positive = true;
	}
	
	public function setNegative()	{
		$this->positive = false;
	}
	
	public function toString()	{
		$string = array();
		$string[] = $this->buildString ('years', $this->years);
		$string[] = $this->buildString ('months', $this->months);
		$string[] = $this->buildString ('weeks', $this->weeks);
		$string[] = $this->buildString ('days', $this->days);
		$string[] = $this->buildString ('hours', $this->hours);
		$string[] = $this->buildString ('minutes', $this->minutes);
		$string[] = $this->buildString ('seconds', $this->seconds);
		return join (', ', $string);  
	}
	
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
	
	public function buildString ($key, $value)	{
		return ($this->positive ? $value : (0 - $value)) . ' ' . $key;
	}
		
	public function __toString() {
		return $this->toString();
	}

}