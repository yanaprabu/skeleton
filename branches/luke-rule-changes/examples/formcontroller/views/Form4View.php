<?php

class Form4View extends A_Http_View {
	protected $values = array();
	protected $errmsgs = array();
	
	function __construct($locator) {
        parent::__construct($locator);
	}

	function setValues($values) {
		$this->values = $values;
	}
	
	function setErrorMsg($errmsgs) {
		$this->errmsgs = $errmsgs;
	}
	
	function render() {
		dump($this->errmsgs, 'ERROR MESSAGES: ');
		dump($this->values, 'VALUES: ');
	}

}

