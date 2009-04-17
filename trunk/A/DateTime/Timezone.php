<?php
/**
 * Date & Time Duration functionality 
 *
 *
 * @package A_Datetime
 */
	
class A_DateTime_Timezone {
	
	protected $reference;
	protected $target;
	protected $errmsg;
	
	/*
	 * Configuration at instatiation with timezone string or null for PHP default timezone
	 */
	public function __construct ($name='')	{
	    if ($name == '') {
	    	$name = date_default_timezone_get();
		}
	    try {
			$this->reference = new DateTimeZone($name);
		} catch(Exception $e) {
			$this->errmsg = $e->getMessage();
		}
	}
	
	/*
	 * return the string name of the reference timezone
	 */
	public function getName ()	{
	    if (isset($this->reference)) {
			return $this->reference->getName();
		}
	}

	/*
	 * return the UTC offset of the reference timezone
	 */
	public function getOffset ()	{
	    if (isset($this->reference)) {
	    	return $this->reference->getOffset(new DateTime("now", $this->reference)) / 3600;
		}
	}

	/*
	 * set the target timezone by name
	 */
	public function setTargetName ($name='')	{
	    if ($name == '') {
	    	$name = date_default_timezone_get();
		}
	    try {
			$this->target = new DateTimeZone($name);
		} catch(Exception $e) {
			$this->errmsg = $e->getMessage();
		}
	}

	/*
	 * return the string name of the target timezone
	 */
	public function getTargetName ()	{
	    if (isset($this->target)) {
			return $this->target->getName();
		}
	}

	/*
	 * return the UTC offset of the target timezone
	 */
	public function getTargetOffset ()	{
	    if (isset($this->target)) {
	    	return $this->target->getOffset(new DateTime("now", $this->target)) / 3600;
		}
	}

	/*
	 * return the difference between the reference and target offsets
	 */
	public function getDifference ()	{
    	return $this->getTargetOffset() - $this->getOffset();
	}

}