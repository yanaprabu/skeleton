<?php
include_once('A/Template.php');
/**
 * Template class that includes PHP templates. No block support.
 * 
 * @package A_Template 
 */

class A_Template_Include extends A_Template {

	public function render() {
	    extract($this->data);
		ob_start();
	    include(func_num_args() ? func_get_arg(0) : $this->filename);
	    return ob_get_clean();
	}

}