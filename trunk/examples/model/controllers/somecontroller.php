<?php
require_once 'A/Controller/Action/Dispatch.php';
include_once 'A/Model/Form.php';
require_once 'A/Validator.php';
require_once 'A/FilterChain.php';
require_once 'A/Html/Form.php';
require_once 'A/Filter/Alnum.php';
require_once 'A/Filter/Digit.php';
require_once 'A/Filter/Trim.php';
require_once 'A/Rule/Digit.php';
require_once 'A/Rule/Inarray.php';
require_once 'A/Rule/Length.php';
require_once 'A/Rule/Match.php';

class Somecontroller extends A_Controller_Action_Dispatch {
	
	function run($locator) {
		
		$usersmodel = $this->load()->model('Users');	
		$view = $this->load()->view('Form');
		
		// Instantiate a new form model/controller
		$form = new A_Model_Form();
		// Hand the Form the fields and rules from the model
		//	$form->addRule($usersmodel->getRules()); 
		$form->addField($usersmodel->getFields()); 	
	
		// Now add an additional field, the second password field. Which must match the first password field. 
		// The $form get the Rules for the first password field from $usersmodel 
		$form->addField($passwordfield = new A_Model_Form_Field('password2'));
		// now we add an additional rule, specific for the form we are dealing with.
		$form->addRule(new A_Rule_Match('password', 'password2', 'Password 2 must match Password 1'));
				
		$form->run($locator);
				
		// ask the form if it is valid. The form checks internally if the model fields are valid?
		if($form->isValid()){
			// save
			$usersmodel->save($form->getSaveValues());	
			// redirect to user detail page or whatever
		} else {
			// show errors if submitted
			$view->setErrorMsg($form->getErrorMsg());
		}
		$view->setValues($form->getValues());
		
		$this->response->setRenderer($view);		
	}	
	
}