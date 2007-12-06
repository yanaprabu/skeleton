<?php
require_once('A/DL.php');
require_once('A/Filter.php');
require_once('A/Rule.php');
require_once('A/Rule/Match.php');
require_once('A/Rule/Range.php');
require_once('A/Rule/Length.php');
require_once('A/Controller/Form.php');

class Form1 extends A_Controller_Form {

	function __construct() {
/*
		$handlers = array(
			'init' => new Handle('views/Form1View.php', 'Form1View', 'init'), 
			'submit' => new Handle('views/Form1View.php', 'Form1View', 'submit'), 
			'done' => new Handle('views/Form1View.php', 'Form1View', 'done')
			);
*/
	$handlers = array(
			'init' => new A_DLInstance($this, '_init'), 
			'submit' => new A_DLInstance($this, '_submit'), 
			'done' => new A_DLInstance($this, '_done')
			);
		parent::__construct($handlers);
	}
	
	function run($locator) {
		// get parameter object from controller
		$this->addParameter($param1 = new A_Controller_FormParameter('field1'));
		$param1->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$param1->addRule(new A_Rule_NotNull('field1', 'Please enter Field 1'));
		$param1->addRule(new A_Rule_Range('field1', 1, 10, 'Field 1 must be 1-10'));
		$param1->setType(array('renderer'=>'A_Html_Form_Select', 'values'=>array(5, 10, 15), 'labels'=>array('five', 'ten', 'fifteen')));
		
		$this->addParameter($param2 = new A_Controller_FormParameter('field2'));
		$param2->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$param2->addRule(new A_Rule_NotNull('field2', 'Please enter Field 2'));
		$param2->addRule(new A_Rule_Match('field2', 'field1', 'Field 2 must match Field 1'));
		$param2->setType(array('renderer'=>'A_Html_Form_Text','size'=>'10'));
		
		$this->addParameter($param3 = new A_Controller_FormParameter('field3'));
		$param3->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$param3->addRule(new A_Rule_Length('field3', 5, 20, 'Field 3 must be 5-20 characters'));
		
		// create parameter object then add it to the controller
		$this->addParameter($param4 = new A_Controller_FormParameter('field4'));
		$param4->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$param4->addFilter(new A_Filter_ToUpper());
		$param4->addRule(new A_Rule_NotNull('field4', 'Please enter Field 4'));
		
		parent::run($locator);
	}

/*
 * State Handler Classes
 */

	function _init($locator) {
		echo 'InitHandler: STATE INIT<br/>';
		$controller = $locator->get('Controller');
		
		$this->setParameterValue('field1', 15);
		$this->setParameterValue('field2', 'init');
		$this->setParameterValue('field3', 'init');
		$this->setParameterValue('field4', 'init');

		include 'templates/example_form.php';
	}
	
	function _submit($locator) {
		echo 'SubmitHandler: STATE SUBMITTED<br/>';
		$controller = $locator->get('Controller');
	
		include 'templates/example_form.php';
	}
	
	function _done($locator) {
		echo 'DoneHandler: STATE DONE<br/><br/><a href="../">Return to Examples</a>';
	}
	
}

?>