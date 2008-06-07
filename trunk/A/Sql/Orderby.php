<?php

require_once 'A/Sql/Columns.php';

class A_Sql_Orderby extends A_Sql_Columns {
	public function render() {
		return 'ORDER BY '. parent::render();
	}
}
