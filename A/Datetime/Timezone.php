<?php
/**
 * Timezone.php
 *
 * @package  A_Datetime
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Eran Galt, Cory Kaufman, Christopher Thompson
 */

/**
 * A_Datetime_Timezone
 * 
 * Date & Time Timezone functionality
 */
class A_Datetime_Timezone
{

	protected $reference;
	protected $target;
	protected $errorMsg;
	
	/**
	 * Configuration at instatiation with timezone string or null for PHP default timezone
	 * 
	 * @param string $name Timezone ID
	 */
	public function __construct($name='')
	{
	    if ($name == '') {
	    	$name = date_default_timezone_get();
		}
	    try {
			$this->reference = new DateTimeZone($name);
		} catch(Exception $e) {
			$this->errorMsg = $e->getMessage();
		}
	}
	
	/**
	 * Return the string name of the reference timezone
	 * 
	 * @return string
	 */
	public function getName()
	{
	    if (isset($this->reference)) {
			return $this->reference->getName();
		}
	}
	
	/**
	 * Return the UTC offset of the reference timezone
	 * 
	 * @return int
	 */
	public function getOffset()
	{
	    if (isset($this->reference)) {
	    	return $this->reference->getOffset(new DateTime("now", $this->reference)) / 3600;
		}
	}
	
	/**
	 * Set the target timezone by name
	 * 
	 * @param string $name
	 */
	public function setTargetName($name='')
	{
	    if ($name == '') {
	    	$name = date_default_timezone_get();
		}
	    try {
			$this->target = new DateTimeZone($name);
		} catch(Exception $e) {
			$this->errorMsg = $e->getMessage();
		}
	}
	
	/**
	 * Return the string name of the target timezone
	 * 
	 * @return string
	 */
	public function getTargetName()
	{
	    if (isset($this->target)) {
			return $this->target->getName();
		}
	}
	
	/**
	 * Return the UTC offset of the target timezone
	 * 
	 * @return int
	 */
	public function getTargetOffset()
	{
	    if (isset($this->target)) {
	    	return $this->target->getOffset(new DateTime("now", $this->target)) / 3600;
		}
	}
	
	/**
	 * Return the difference between the reference and target offsets
	 * 
	 * @return int
	 */
	public function getDifference()
	{
    	return $this->getTargetOffset() - $this->getOffset();
	}

}
