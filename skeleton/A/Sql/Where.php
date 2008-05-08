<?php

require_once 'A/Sql/LogicalList.php';

class A_Sql_Where extends A_Sql_LogicalList {
	public function render() {
		return 'WHERE '. parent::render();
	}
}