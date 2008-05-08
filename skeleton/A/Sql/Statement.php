<?php

class A_Sql_Statement {
	protected $db;
	protected $escapeListeners = array();	
		
	public function setDb($db) {
		$this->db = $db;
		if (count($this->escapeListeners)) {
			foreach ($this->escapeListners[0] as $listener) {
				$listener->setDb($this->db);
			}
		}
		return $this;
	}

	protected function condition(&$list, $arg1, $arg2=null, $arg3=null) {
		if ($arg1) {	
			if ($arg3 === null) {
				$logic = 'AND';		// if no 3rd arg is not set then logic is AND
			} else {
				$logic = $arg1;		// if 3rd arg is set then it is logic
				$arg1 = $arg2;		// move args down
				$arg2 = $arg3;
			}
				
			include_once('A/Sql/Expression.php');
			$expression = new A_Sql_Expression($arg1, $arg2);
			$this->escapeListeners[] = $expression; 		
			if ($list) {
				$list[] = $logic;
	        }
			$list[] = $expression;
		}    
        return $this;
    }
}