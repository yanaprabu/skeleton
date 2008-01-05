<?php

include_once 'A/Sql/Abstract.php';

class A_Sql_List extends A_Sql_Abstract {
	protected $element;

	public function __construct($element) {
		$this->element = $element;
	}
	
	public function render() {
		$list = is_object($this->element) ? $this->element->render() : $this->element;
		return is_array($list) ? implode(', ', $list) : $list;
	}
}
