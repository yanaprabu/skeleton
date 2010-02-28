<?php

class Somecontroller extends A_Controller_Action {
	
	function index($locator) {
		
		$usersmodel = $this->_load()->model('Users');	
		$view = $this->_load()->view('Form');

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
				
		//$form->run($locator);
			//dump($form);	
		// ask the form if it is valid. The form checks internally if the model fields are valid?
		if($form->isValid($this->request)){
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