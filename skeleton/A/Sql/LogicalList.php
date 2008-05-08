<?php

class A_Sql_LogicalList {
	public function __construct($data) {
		$this->data = (array)$data;
	}
	
	public function render() {
		$output = array();
		if (!count($this->data)) return;
		foreach ($this->data as $data) {
			$output[] = is_object($data) ? '('. $data->render() .')' : strtoupper($data);
		}
		return implode(' ', $output);
	}


}