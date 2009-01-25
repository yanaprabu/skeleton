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
		if( is_array($years) ) {
			$this->fromArray ($years);
		} else if ( is_string($years) ) {
			$this->fromString($years);
		} else {
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
				$parts[$partName] = $this -> $partName = $num;
			}
		}
		return $parts;
	}
	
	public function fromArray ($parts)	{
		foreach($this -> _partNames as $part) {
			$this -> $part = isset($parts[$part]) ? $parts[$part] : 0;
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
		foreach($this -> _partNames as $part) {
			$value = $this -> $part;
			$string[] = $this->positive ? $value : ((0 - $value) . ' ' . $part);
		}
		return implode(', ', $string);  
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
			
	public function __toString() {
		return $this->toString();
	}

}