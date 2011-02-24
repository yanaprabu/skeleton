<?php
#include_once "A/Controller/Helper/Abstract.php";

class fooHelper extends A_Controller_Helper_Base {
	
	public function bar($arg='') {
		return "helper fooHelper::bar() called. ARG=" . strtoupper($arg) . "<br/>";
	}
}