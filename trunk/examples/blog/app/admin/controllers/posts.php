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
		
		$template = $this->_load()->template();
		
		// create a data object that has the interface needed by the Pager object
		$datasource = new A_Pagination_Adapter_Db($db, "SELECT * FROM posts WHERE users_id=" . $usersession->get('id'));
		 
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
		$template = $this->_load()->template();
		
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