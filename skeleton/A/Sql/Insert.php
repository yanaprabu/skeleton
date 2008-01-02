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
	 * setEquation
	*/	
	protected $setEquation;

	/**
	 * table()
	*/	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Piece/Table.php');
		$this->table = new A_Sql_Piece_Table($table);
		return $this;
	}

	/**
	 * values()
	*/	
	public function values($data, $value=null) {
		if (!$this->setEquation) include_once ('A/Sql/Piece/Equation.php');
		if (!$this->set) include_once('A/Sql/Piece/List.php');
		$this->setEquation = new A_Sql_Piece_Equation($data, $value);	
		$this->set = new A_Sql_Piece_List($this->setEquation);
		return $this;
	}
	
	public function render($db=null) {
		if (!$this->table || !$this->set) {
			return;
		}
		$this->set->setLogic(', ');
		$this->setEquation->setEscapeCallback($db);
		return sprintf('INSERT INTO %s SET %s', $this->table->render(), $this->set->render());
	}
}
