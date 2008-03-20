<?php

abstract class A_Sql_Abstract {

	public function escape($value) {
		return addslashes($value);
	}

	/** 
	 * quoteValue
	*/
	public function quoteValue($value) {
		$value = trim($value, '\''); //incase the user already quoted the value
		return '\''. $this->escape($value) .'\'';
	}	

    public function render() {
    }

	public function __toString() {
		return $this->render();
	}

}
