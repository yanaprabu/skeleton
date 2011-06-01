<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Alnum.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Alnum
 * 
 * Filter an array of values using provided filter
 * 
 * @package A_Filter 
 */
class A_Filter_Iterator extends A_Filter_Base {
protected $filter;
	
    public function __construct($filter) {
		$this->filter = $filter;
    }

    public function filter() {
		if (is_array($this->getValue())) {
			$data = array();
			foreach ($this->getValue() as $key => $value) {
				$data[$key] = $this->filter->run($value);
			}
			return $data;
		} else {
			return $this->filter->run($this->getValue());
		}
    }
}
