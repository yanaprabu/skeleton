<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');
class example extends A_Controller_Action {
	protected $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$this->load()->response()->view();
	}

	function bar($locator) {
		$model = $this->load()->model();
		$template = $this->load('global')->template();
		$template->set('model', $model);
		$this->response->setRenderer($template);
	}

}
