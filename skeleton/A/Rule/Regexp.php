<?php
if (! class_exists('A_Rule_Abstract')) include 'A/Rule/Abstract.php';

class A_Rule_Regexp extends A_Rule_Abstract {
	protected $regex;

    public function __construct($field, $regexp, $errorMsg) {
		$this->field = $field;
		$this->regexp = $regexp;
		$this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
		return (preg_match($this->regexp, $container->get($this->field)));
	}
}
