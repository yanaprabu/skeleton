<?php

require_once 'A/Sql/Expression.php';

class A_Sql_Values extends A_Sql_Expression {
	public function render() {
		$columns = implode(', ', array_keys($this->data));
		$values = implode(", ", array_map(array($this, 'quoteEscape'), array_values($this->data)));
		return "(". $columns .") VALUES (". $values .")";
	}
}


?>