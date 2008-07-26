<?php
/**
 * Filter an array of values using provided filter
 * 
 * @package A_Filter 
 */

class A_Filter_Iterator {
protected $filter;
	
    public function __construct($filter) {
		$this->filter = $filter;
    }

    public function run($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = $this->filter->run($value);
			}
			return $data;
		} else {
			return $this->filter->run($data);
		}
    }
}
