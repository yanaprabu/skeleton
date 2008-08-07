<?php
include_once 'A/Model/Request.php';
require_once('A/Filter/Regexp.php');
require_once('A/Filter/Toupper.php');
require_once('A/Rule/Notnull.php');
require_once('A/Rule/Match.php');
require_once('A/Rule/Range.php');
require_once('A/Rule/Length.php');

class FormModel extends A_Model_Request {
	function __construct($locator=null) {
		// get fieldeter object from controller
		$this->addField($field1 = new A_Model_Request_Field('field1'));
		$field1->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$field1->addRule(new A_Rule_NotNull('field1', 'Please enter Field 1'));
		$field1->addRule(new A_Rule_Range('field1', 1, 10, 'Field 1 must be 1-10'));
		$field1->setType(array('renderer'=>'A_Html_Form_Select', 'values'=>array(5, 10, 15), 'labels'=>array('five', 'ten', 'fifteen')));
		
		$this->addField($field2 = new A_Model_Request_Field('field2'));
		$field2->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$field2->addRule(new A_Rule_NotNull('field2', 'Please enter Field 2'));
		$field2->addRule(new A_Rule_Match('field2', 'field1', 'Field 2 must match Field 1'));
		$field2->setType(array('renderer'=>'A_Html_Form_Text','size'=>'10'));
		
		$this->addField($field3 = new A_Model_Request_Field('field3'));
		$field3->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$field3->addRule(new A_Rule_Length('field3', 5, 20, 'Field 3 must be 5-20 characters'));
		
		// create fieldeter object then add it to the controller
		$this->addField($field4 = new A_Model_Request_Field('field4'));
		$field4->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$field4->addFilter(new A_Filter_ToUpper());
		$field4->addRule(new A_Rule_NotNull('field4', 'Please enter Field 4'));
		
		$this->excludeRules('field3');
	}
}