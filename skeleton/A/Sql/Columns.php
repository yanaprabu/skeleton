<?php

class A_Sql_Columns {
	protected $columns = array();
	protected $args = array();
	
	public function __construct($args=null) {
		if (is_array($args)) {
			$this->args = $args;
		} else {
			$this->args = func_get_args();
		}
	}
	
	public function render() {
		$this->columns = isset($this->args[0]) && is_array($this->args[0]) ? $this->args[0] : $this->args;
		if (array_search('*', $this->columns) !== false || !count($this->columns)) {
			$this->columns = array('*');
		}
		return implode(', ', $this->columns);
	}

	public function __toString() {
		return $this->render();
	}

}
