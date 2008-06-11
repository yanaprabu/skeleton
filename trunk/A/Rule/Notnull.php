<?php
include_once 'A/Rule/Abstract.php';

class A_Rule_Notnull extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Notnull';
	
	public function isValid($container) {
		$value = $container->get($this->field);
		return $value != '';
	}
}
