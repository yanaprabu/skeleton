<?php

class A_Db_Sql_Join {
	public $table_name1;
	public $field_name1;
	public $table_name2;
	public $field_name2;
	public $join_type;

	public function __construct($table_name1, $field_name1, $table_name2, $field_name2, $join_type='') {
		$this->table_name1 = $table_name1;
		$this->field_name1 = $field_name1;
		$this->table_name2 = $table_name2;
		$this->field_name2 = $field_name2;
		$this->join_type = $join_type;
	}

	public function getSQL() {
		return " {$this->join_type} JOIN {$this->table_name2} ON {$this->table_name1}.{$this->field_name1}={$this->table_name2}.{$this->field_name2}";
	}

}
