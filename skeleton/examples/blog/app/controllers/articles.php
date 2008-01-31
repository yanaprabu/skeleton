<?php

class articles extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$this->load()->response()->view();
	}

	function all($locator) {
		
		$model = $this->load()->model('articlesModel');
		$content = $model->listAll();
		$template = $this->load()->template();
		$template->set('content', $content);
		$this->response->setRenderer($template);
		
	}
	
}