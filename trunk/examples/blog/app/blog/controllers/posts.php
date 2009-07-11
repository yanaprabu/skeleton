<?php

class posts extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
	}
	
	/* Default action: show list of latest */
	function index($locator) {
		$action = $this->request->get('action');
		
		// If there's a request for a single post
		if( $this->request->has('action') && is_numeric($this->request->get('action')) ){ 
			
			// How to translate URL in correct action variable?
			$model = $this->_load()->model('postsModel');
			$content = $model->single();
			$template = $this->_load()->template('singlePost');
			$template->set('content', $content);
			$maincontent = $template->render();
			
			$this->response->set('maincontent', $template);
			$this->response->set('subcontent','This is the subcontent');

		} 
		else  // show all posts 
		{
			$model = $this->_load()->model('postsModel');
			$content = $model->listAll();
			$template = $this->_load()->template();
			$template->set('content', $content);
			$maincontent = $template->render();		

			$this->response->set('maincontent', $maincontent);
			$this->response->set('subcontent','This is the subcontent');

		}
				
	}

}