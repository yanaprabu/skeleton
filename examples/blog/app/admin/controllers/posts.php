<?php

class posts extends A_Controller_Action {

	/*
	 * This function is called only if it exists. Front Controller pre-filter 
	 * calls it to get required groups for this controller
	 */
	public function _requireGroups(){
		return array('post');
	}
	
	function index($locator) { 
		$template = $this->_load()->template();
		
		$this->listing($locator);
	}

	function listing($locator) {
		$usersession = $locator->get('UserSession');
		$db = $locator->get('Db');
		
		$template = $this->_load('controller')->template('listing');
		
		// create a data object that has the interface needed by the Pager object
		$datasource = new A_Pagination_Adapter_Db($db, "SELECT * FROM posts WHERE post_type='post'");
		 
		// create a request processor to set pager from GET parameters
		$pager = new A_Pagination_Request($datasource);
		 
		// initialize using values from $_GET
		$pager->process($locator->get('Request'));
		 
		// create a "standard" view object to create pagination links
		$view = new A_Pagination_View_Standard($pager);
		 
		$rows = $pager->getItems();

		$template->set('rows', $rows);
		$template->set('links', $view->render());		// display the pagination links
		
		$this->response->set('maincontent', $template->render());

	}

	function edit($locator) { 
		$template = $this->_load('controller')->template();
		$posts = $this->_load('app')->model('posts', $locator->get('Db'));
		
		$form = new A_Model_Form();
		// Hand the Form the fields and rules from the model
		//	$form->addRule($usersmodel->getRules()); 
		$form->addField($posts->getFields()); 	
	
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
		} elseif (! $this->_request()->has('save') && $this->_request()->has('id')) {
			$id = $this->_request()->has('id');
			// load data
			$rows = $posts->find($id);
			if (isset($rows[0])) {
				foreach ($rows[0] as $name => $value) {
					$form->newField($name)->setValue($value);
				}
			}
		}
		$template->set('errorMsg', $form->getErrorMsg(', '));
		$template->import($form->getValues());
		
		$this->response->set('maincontent', $template->render());

	}

/*
id
post_date
permalink
title
excerpt
content
comments_allowed
post_type
users_id
active
*/
}