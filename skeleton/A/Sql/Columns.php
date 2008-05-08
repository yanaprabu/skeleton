<?php

class A_Sql_Columns {
	protected $columns = array();
	protected $args = array();
	
	public function __construct($args=null) {
		if (is_array($args)) {
			$this->columns = $args;
		} else {
			$this->columns = func_get_args();
		}
	}
	
	public function render() {
		if ($this->columns) {
			if (is_array($this->columns) && count($this->columns)) {
				$this->columns = implode(', ', $this->columns);
			}
			return $this->columns;
		}
	}

	public function __toString() {
		return $this->render();
	}

}
