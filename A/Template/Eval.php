<?php
#include_once('A/Template/File.php');
/**
 * Eval.php
 *
 * @package  A_Template
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Template_Eval
 * 
 * Template class that loads and eval()'s PHP templates. Templates can have blocks.
 */
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
