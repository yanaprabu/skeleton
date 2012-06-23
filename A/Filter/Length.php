<?php
/**
 * Length.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Length
 *
 * Trim value to specified length.
 *
 * @package A_Filter
 */
class A_Filter_Length extends A_Filter_Base
{

	protected $length = 0;

	public function __construct($length)
	{
		$this->length = $length;
	}

	public function filter()
	{
		$value = $this->getValue();
		if ($this->length < strlen($value)) {
			$value = substr($value, 0, $this->length);
		}
		return $value;
	}

}
