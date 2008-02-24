<?php

class A_Sql_Join {
	public $table1;
	public $field1;
	public $table2;
	public $field2;
	public $joinType;
	protected $joinFormat = '%s JOIN `%s` ON `%s`.`%s`=`%s`.`%s`';
	
	public function __construct($table1, $field1, $table2, $field2, $joinType='') {
		$this->table1 = $table1;
		$this->field1 = $field1;
		$this->table2 = $table2;
		$this->field2 = $field2;
		$this->joinType = $joinType;
	}

	public function render() {
		return sprintf($this->joinFormat, $this->joinType, $this->table2, $this->table1, $this->field1, $this->table2, $this->field2);
	}

}