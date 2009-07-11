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
		
		$this->response->set('maincontent', $template->render());

	}

}