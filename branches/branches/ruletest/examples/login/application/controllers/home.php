<?php

class home extends A_Controller_Action {

	function index($locator) {
		
		$layout = $this->_load()->template('home.tpl');
		$message = $this->_flash('Message');
		
		$layout->set('message', $message);

		$this->response->setRenderer($layout);		
	}

}