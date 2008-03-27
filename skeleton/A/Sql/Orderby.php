<?php

class A_Sql_Orderby {
	protected $columns = null;
	
	public function __construct($columns) {
		if (is_array($columns)) {
			$this->columns = $columns;
		} else {
			$this->columns = func_get_args();
		}
	}
	
	public function render() {
		if ($this->columns) {
			if (is_array($this->columns)) {
				$this->columns = implode(', ', $this->columns);
			}
			return ' ORDER BY ' . $this->columns;
		}
	}

	public function __toString() {
		return $this->render();
	}

}