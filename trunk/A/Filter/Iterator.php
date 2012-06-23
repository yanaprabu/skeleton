<?php
/**
 * Alnum.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Alnum
 *
 * Filter an array of values using provided filter
 *
 * @package A_Filter
 */
class A_Filter_Iterator extends A_Filter_Base
{

	protected $filter;

	public function __construct($filter)
	{
		$this->filter = $filter;
	}

	public function filter()
	{
		$value = $this->container;
		if (is_array($value)) {
			$data = array();
			foreach ($value as $key => $indexValue) {
				$data[$key] = $this->filter->doFilter($indexValue);
			}
			return $data;
		} else {
			return $this->filter->doFilter($value);
		}
	}

}
