<?php

class A_Sql_List {
	protected $element;

	public function __construct($element) {
		$this->element = $element;
	}
	
	public function render() {
		$list = is_object($this->element) ? $this->element->render() : $this->element;
		return is_array($list) ? implode(', ', $list) : $list;
	}

	public function __toString() {
		return $this->render();
	}

}
