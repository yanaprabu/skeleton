<?php

class posts extends A_Controller_Action {
	protected $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action: show list of latest */
	function index($locator) {
		
		$request = $locator->get('Request');
		$action = $request->get('action');
		
		// If there's a request for a single post
		if( $request->has('action') && is_numeric($request->get('action')) ){ 
			
			// How to translate URL in correct action variable?
			$model = $this->load()->model('postsModel');
			$content = $model->single();
			$template = $this->load()->template('singlePost');
			$template->set('content', $content);
			$maincontent = $template->render();
			
			$this->response->set('maincontent', $template);
			$this->response->set('subcontent','This is the subcontent');

		} 
		else  // show all posts 
		{
			$model = $this->load()->model('postsModel');
			$content = $model->listAll();
			$template = $this->load()->template();
			$template->set('content', $content);
			$maincontent = $template->render();		

			$this->response->set('maincontent', $maincontent);
			$this->response->set('subcontent','This is the subcontent');

		}
				
	}

	
}