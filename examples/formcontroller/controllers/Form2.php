<?php
require_once('A/DL.php');
require_once('A/Filter/Regexp.php');
require_once('A/Filter/Toupper.php');
require_once('A/Rule/Notnull.php');
require_once('A/Rule/Match.php');
require_once('A/Rule/Range.php');
require_once('A/Rule/Length.php');
require_once('A/Model/Request.php');
require_once('A/Html/Form.php');

class Form2 {

	function __construct($locator=null) {
	}
	
	function run($locator) {
		$model = new A_Model_Request();
		// get fieldeter object from controller
		$model->addField($field1 = new A_Model_Request_Field('field1'));
		$field1->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$field1->addRule(new A_Rule_Notnull('field1', 'Please enter Field 1'));
		$field1->addRule(new A_Rule_Range(1, 10, 'field1', 'Field 1 must be 1-10'));
		$field1->setType(array('renderer'=>'A_Html_Form_Select', 'values'=>array(5, 10, 15), 'labels'=>array('five', 'ten', 'fifteen')));
		
		$model->addField($field2 = new A_Model_Request_Field('field2'));
		$field2->addFilter(new A_Filter_Regexp('/[^0-9]/', ''));
		$field2->addRule(new A_Rule_Notnull('field2', 'Please enter Field 2'));
		$field2->addRule(new A_Rule_Match('field1', 'field2', 'Field 2 must match Field 1'));
		$field2->setType(array('renderer'=>'A_Html_Form_Text','size'=>'10'));
		
		$model->addField($field3 = new A_Model_Request_Field('field3'));
		$field3->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$field3->addRule(new A_Rule_Length(5, 20, 'field3', 'Field 3 must be 5-20 characters'));
		
		// create fieldeter object then add it to the controller
		$model->addField($field4 = new A_Model_Request_Field('field4'));
		$field4->addFilter(new A_Filter_Regexp('/[^a-zA-Z]/', ''));
		$field4->addFilter(new A_Filter_ToUpper());
		$field4->addRule(new A_Rule_Notnull('field4', 'Please enter Field 4'));
		
		$model->excludeRules('field3');
		$model->run($locator);
		
		if ($model->isValid()) {
			echo 'DONE<br/><br/><a href="../">Return to Examples</a>';
		} else {
			if (! $model->isSubmitted()) {
				$model->set('field1', 15);
				$model->set('field2', 'init');
				$model->set('field3', 'init');
				$model->set('field4', 'init');
			}

			// create HTML form generator
			$form = new A_Html_Form();
			$form->setModel($model)
					->setWrapper('A_Html_Div', array('class'=>'fieldclass', 'style'=>'border:1px solid red;'))
					->select(array('name'=>'field1', 'values'=>array(5,10,15), 'labels'=>array('five','ten','fifteen'), 'id'=>'field1', 'label'=>'Field 1 (Numbers only in range 1-10)', 'after'=>$field1->getErrorMsg(', ')))
					->text(array('name'=>'field2', 'id'=>'field2', 'label'=>'Field 2 (Must match Field 1)', 'after'=>$field2->getErrorMsg(', ')))
					->text(array('name'=>'field3', 'id'=>'field3', 'label'=>'Field 3 (Letters only min length 5)', 'after'=>$field3->getErrorMsg(', ')))
					->text(array('name'=>'field4', 'id'=>'field4', 'label'=>'Field 4 (Convert letters to uppercase)', 'after'=>$field4->getErrorMsg(', ')))
					->submit('submit', 'Submit');
			echo $form->render();
#			include 'templates/example_form2.php';
		}
	}

}
