<?php
/**
 * Date & Time Duration public functionality 
 *
 *
 * @package A_Datetime
 */
	
class A_DateTime_Duration {
	
	protected $_partNames = array('years','months','weeks','days','hours','minutes','seconds');
	protected $years;
	protected $months;
	protected $weeks;
	protected $days;
	protected $hours;
	protected $minutes;
	protected $seconds;
	protected $positive = true;
	
	public function __construct ($years = 0, $months = 0, $weeks = 0, $days = 0, $hours = 0, $minutes = 0, $seconds = 0)	{
		if (func_num_args() == 1 && is_array (get_func_arg (0)))	{
			extract (get_func_arg (0));
		} elseif (func_num_args() == 1 && is_string (get_func_arg (0)))	{
			extract ($this->fromString (get_func_arg (0)));
		}
	
		$this->years = $years;
		$this->months = $months;
		$this->weeks = $weeks;
		$this->days = $days;
		$this->hours = $hours;
		$this->minutes = $minutes;
		$this->seconds = $seconds;
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
		return $parts;
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
	
	public function buildString ($key, $value)	{
		return ($this->positive ? $value : (0 - $value)) . ' ' . $key;
	}
		
	public function __toString() {
		return $this->toString();
	}

}