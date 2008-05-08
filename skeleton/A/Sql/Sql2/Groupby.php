<?php

require_once 'A/Sql/Columns.php';

class A_Sql_Groupby extends A_Sql_Columns {
	public function render() {
		return 'GROUP BY '. parent::render();
	}
}

?>