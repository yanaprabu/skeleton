<?php
/**
 * Trim.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Trim
 * 
 * String loading and trailing whitespace with the trim() function.
 */
class A_Filter_Trim extends A_Filter_Base
{

	protected $charset = null;
	
	public function __construct($charset=null)
	{
		$this->charset = $charset;
	}
	
	public function filter()
	{
		return trim($this->getValue(), $this->charset);
	}

}
