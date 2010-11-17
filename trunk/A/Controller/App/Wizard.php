<?php
/*
Use A_Controller_App and craete a Rule A_Controller_App_Wizard that keeps track of the current step in the sequence.

Every step should have a array('controller','action') forward defined. There should be a setStep($n, $forward)
*/

class A_Controller_App_Wizard {
	protected $maxStep = -1;
	
	/*
	 * 
	 */
	function __construct() {
		
	}

	/*
	 * 
	 */
	function setStep($position, $forward) {
		$this->_steps[$postion] = $forward;
		if ($position > $this->maxStep) {
			$this->maxStep = $position;
		}
	}

	/*
	 * 
	 */
	function addStep($forward) {
		$this->_steps[++$this->maxStep] = $forward;
	}
	
	/*
	 * 
	 */
	function isValid() {
		
	}
}