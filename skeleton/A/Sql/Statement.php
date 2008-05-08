<?php

class A_Sql_Statement {
	protected $db;
	protected $escapeListeners = array();	
	
	protected function condition(&$list, $arg1, $arg2=null, $arg3=null) {
		if ($arg1) {	
			if (($arg3 === null) && ! is_array($arg2)) {
				$logic = 'AND';		// if 3rd null and arg2 not an array then it is ('field', 'value')
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

	public function setDb($db) {
		$this->db = $db;
		return $this;
	}

	protected function notifyListeners() {
		if (count($this->escapeListeners)) {
			foreach ($this->escapeListeners as $listener) {
				$listener->setDb($this->db);
			}
		}
	}
}