<?php

class example extends A_Controller_Action {

	function index($locator) {
		$view = $this->_load()->view();
		$view->set('title', 'This is the title set in the controller');	
		echo $view->render();
	}
}

