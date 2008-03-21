<?php

class A_Sql_Orderby {
	protected $columns = null;
	
	public function __construct($columns) {
		$this->columns = $columns;
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