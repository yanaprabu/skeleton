<?php

class home extends A_Controller_Action {

	function run($locator) {
		$layout = $this->load()->template('home.tpl');
		$message = $this->flash('Message');
		
		$layout->set('message', $message);

		$this->response->setRenderer($layout);		
	}

}