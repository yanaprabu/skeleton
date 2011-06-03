<?php
/**
 * Wizard.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_App_Wizard
 * 
 * Handles sequences of controller/actions to be used with A_Controller_App.  Define each step with an action/controller pair (array('controller', 'action')) using setStep().
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