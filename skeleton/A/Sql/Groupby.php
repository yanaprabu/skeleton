<?php

class A_Sql_Groupby {
	protected $columns = null;
	
	public function __construct($columns) {
		$this->columns = $columns;
	}
	
	public function render() {
		if ($this->columns) {
			if (is_array($this->columns)) {
				$this->columns = implode(', ', $this->columns);
			}
			return ' GROUP BY ' . $this->columns;
		}
	}

	public function __toString() {
		return $this->render();
	}

}