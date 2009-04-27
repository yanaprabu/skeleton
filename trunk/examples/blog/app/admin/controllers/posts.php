<?php

class posts extends A_Controller_Action {
	protected $response;

	/*
	 * This function is called only if it exists. Front Controller pre-filter 
	 * calls it to get required groups for this controller
	 */
	public function _requireGroups(){
		return array('post');
	}
	
	function run($locator) { 
		$template = $this->load()->template();
		
		$locator->get('Response')->set('maincontent', $template->render());

	}

}