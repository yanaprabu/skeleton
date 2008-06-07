<?php
include_once('A/Template.php');

class A_Template_Include extends A_Template {

	public function render() {
	    extract($this->data);
		ob_start();
	    include($this->filename);
	    $str = ob_get_clean();
	    return($str);
	}

}