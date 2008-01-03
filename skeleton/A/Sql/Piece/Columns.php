<?php

include_once 'A/Sql/Piece/Abstract.php';

class A_Sql_Piece_Columns extends A_Sql_Piece_Abstract {
	protected $columns = array();
	
	public function __construct(array $arguments) {
		$this->arguments = $arguments;
	}
	
	public function render() {
		if (!array_search('*', $this->arguments)) {
			$this->columns = is_array($this->arguments[0]) ? $this->arguments[0] : $this->arguments;
		}
		if (count($this->columns)) {
			$this->columns = array_map(array($this, 'quoteName'), $this->columns);
		} else {
			$this->columns = array('*');
		}
		return implode(', ', $this->columns);
	}
}
