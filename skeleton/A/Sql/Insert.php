<?php

class A_Sql_Insert {
	/**
	 * table()
	*/	
	protected $table;
	
	/**
	 * set
	*/	
	protected $set;

	/**
	 * setExpression
	*/	
	protected $setExpression;

	/**
	 * table()
	*/	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	/**
	 * values()
	*/	
	public function values($data, $value=null) {
		if (!$this->setExpression) include_once ('A/Sql/Expression.php');
		if (!$this->set) include_once('A/Sql/List.php');
		$this->setExpression = new A_Sql_Expression($data, $value);	
		$this->set = new A_Sql_List($this->setExpression);
		return $this;
	}
	
	public function render($db=null) {
		if (!$this->table || !$this->set) {
			return;
		}
		$this->setExpression->setEscapeCallback($db);
		return sprintf('INSERT INTO %s SET %s', $this->table->render(), $this->set->render());
	}

	public function __toString() {
		return $this->render();
	}

}
