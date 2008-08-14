<?php
require_once('A/DL.php');
require_once('A/Filter/Regexp.php');
require_once('A/Filter/Toupper.php');
require_once('A/Rule/Notnull.php');
require_once('A/Rule/Match.php');
require_once('A/Rule/Range.php');
require_once('A/Rule/Length.php');
require_once('A/Controller/Form.php');

class Form1 extends A_Controller_Form {

	function __construct($locator=null) {
/*
		$handlers = array(
			'init' => new A_DL('views/Form1View.php', 'Form1View', 'init'), 
			'submit' => new A_DL('views/Form1View.php', 'Form1View', 'submit'), 
			'done' => new A_DL('views/Form1View.php', 'Form1View', 'done')
			);
*/
	$handlers = array(
			'init' => array($this, '_init'), 
			'submit' => array($this, '_submit'), 
			'done' => array($this, '_done')
			);
		parent::__construct($locator, $handlers);
	}
	
	function run($locator) {
		// get parameter object from controller
		$this->addParameter($param1 = new A_Controller_FormParameter('field1'));
		$param1->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$param1->addRule(new A_Rule_NotNull('field1', 'Please enter Field 1'));
		$param1->addRule(new A_Rule_Range(1, 10, 'field1', 'Field 1 must be 1-10'));
		$param1->setType(array('renderer'=>'A_Html_Form_Select', 'values'=>array(5, 10, 15), 'labels'=>array('five', 'ten', 'fifteen')));
		
		$this->addParameter($param2 = new A_Controller_FormParameter('field2'));
		$param2->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$param2->addRule(new A_Rule_NotNull('field2', 'Please enter Field 2'));
		$param2->addRule(new A_Rule_Match('field1', 'field2', 'Field 2 must match Field 1'));
		$param2->setType(array('renderer'=>'A_Html_Form_Text','size'=>'10'));
		
		$this->addParameter($param3 = new A_Controller_FormParameter('field3'));
		$param3->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$param3->addRule(new A_Rule_Length(5, 20, 'field3', 'Field 3 must be 5-20 characters'));
		
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
		
		$this->set('field1', 15);
		$this->set('field2', 'init');
		$this->set('field3', 'init');
		$this->set('field4', 'init');

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