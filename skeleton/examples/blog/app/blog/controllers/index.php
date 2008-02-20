<?php

class index extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action. Shows latest articles */
	function run($locator) {
		$template = $this->load('global')->template('main');
		$this->response->set('layout','articlelayout');
		$this->response->set('maincontent','The maincontent for the blog index');
		$this->response->set('subcontent','This is the subcontent');
	}
	/*
	function run($locator) { 
		$this->load()->response('maincontent')->view();
	}*/	
	function foo($locator) { 
		$this->load()->response()->view();
	}

	function bar($locator) {
		$model = $this->load()->model('DaysModel');
		$template = $this->load()->template();
		$template->set('model', $model);
		$this->response->setRenderer($template);
	}
}