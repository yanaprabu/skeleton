<?php

class admin {

	/*
	 * This function is called only if it exists. Front Controller pre-filter 
	 * calls it to get required groups for this controller
	 */
	public function _requireGroups(){
		return array('post','admin');
	}
	
	function index($locator) { 
		$locator->get('Response')->setPartial('maincontent', 'admin');
	}

}