<?php

class DaysModel {
	protected $months = array(
							1 => 'Sunday',
							2 => 'Monday',
							3 => 'Tuesday',
							4 => 'Wednesday',
							5 => 'Thursday',
							6 => 'Friday',
							7 => 'Saturday',
							);
							
	function numberToName($number) {
		return isset($this->months[$number]) ? $this->months[$number] : false;
	}

	function nameToNumber($name) {
		return array_search($name, $this->months);
	}
}