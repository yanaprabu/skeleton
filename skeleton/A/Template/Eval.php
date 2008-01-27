<?php

include_once('A/Template.php');

class A_Template_Eval extends A_Template_File {
	protected $str = '';
	
	public function render($block='') {
	   	if ($this->auto_blocks) {
	   		$this->makeBlocks();
	   	} else {
	   		$this->loadTemplate();
	   	}
   		if (is_array($this->data) && isset($this->blocks[$block])) {
		    $this->str =& $this->blocks[$block];
		    return($this->evalStr());
   		} else {
   			return $this->blocks[$block];
   		}
	}

	public function evalStr($_template_eval_str) {
		extract($this->data);
	    eval('return "' . addslashes($this->str) . '";');
	}
}
