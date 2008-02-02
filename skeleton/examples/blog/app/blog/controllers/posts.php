<?php

class posts extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action: show list of latest */
	function run($locator) {
		
		$request = $locator->get('Request');
		$action = $request->get('action');
		
		if($request->has('action') && $request->get('action') == 1 ){ 
			$model = $this->load()->model('postsModel');
			$content = $model->single();
			$template = $this->load()->template('singlePost');
			$template->set('content', $content);
			$this->response->setRenderer($template);
		} else {
			$model = $this->load()->model('postsModel');
			$content = $model->listAll();
			$template = $this->load()->template();
			$template->set('content', $content);
			$this->response->setRenderer($template);
		}
		
	}
	
	function showLatest(){
		$model = $this->load()->model('postsModel');
		$content = $model->listAll();
		$template = $this->load()->template();
		$template->set('content', $content);
		$this->response->setRenderer($template);
	}
	
	/* Todo: make this to show all? posts */
	function all($locator) {
		$this->load()->response()->view();
	}
	
}