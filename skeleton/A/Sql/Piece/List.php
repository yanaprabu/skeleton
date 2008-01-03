<?php

include_once 'A/Sql/Piece/Abstract.php';

class A_Sql_Piece_List extends A_Sql_Piece_Abstract {
	protected $glue = ' AND ';
	protected $equation;
		
	public function __construct($equation) {
		$this->equation = $equation;
	}
	
	public function setLogic($logic=null) {
		if ($logic !== null) {
			$this->glue = ' '. trim($logic) .' ';
		}
	}
	
	public function render() {	
		$list = $this->equation->render();
		return is_array($list) ? implode($this->glue, $list) : $list;
	}
}
