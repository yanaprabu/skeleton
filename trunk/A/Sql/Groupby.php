<?php

require_once 'A/Sql/Columns.php';

class A_Sql_Groupby extends A_Sql_Columns {
	protected $columns;
	
	public function render() {
		if ($this->columns) {
			return 'GROUP BY '. parent::render();
		}
	}
}
