<?php
include_once "A/Controller/Helper/Abstract.php";

class fooHelper extends A_Controller_Helper_Abstract {
	
	public function bar() {
		echo "helper foo::bar() called.<br/>";
	}
}