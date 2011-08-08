<?php
/**
 * Wizard.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_App_Wizard
 * 
 * Handles sequences of controller/actions to be used with A_Controller_App.  Define each step with an action/controller pair (array('controller', 'action')) using setStep().
 * 
 * @package A_Controller
 */
class A_Controller_App_Wizard
{

	protected $maxStep = -1;

	public function setStep($position, $forward)
	{
		$this->_steps[$postion] = $forward;
		if ($position > $this->maxStep) {
			$this->maxStep = $position;
		}
	}
	
	public function addStep($forward)
	{
		$this->_steps[++$this->maxStep] = $forward;
	}
	
	function isValid()
	{}
}
