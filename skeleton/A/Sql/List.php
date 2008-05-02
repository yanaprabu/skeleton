<?php

class A_Sql_List {
	protected $db;
	protected $element;

	public function __construct($element) {
		$this->element = $element;
	}
	
	public function render() {
		$list = is_object($this->element) ? $this->element->setDb($this->db)->render() : $this->element;
		return is_array($list) ? implode(', ', $list) : $list;
	}
	
	public function setDb($db) {
		$this->db = $db;
	}

	public function __toString() {
		return $this->render();
	}
}
