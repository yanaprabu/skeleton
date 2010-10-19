<?php

class posts extends A_Controller_Action {

	/* Default action: show list of latest */
	function index($locator) {
		$action = $this->request->get('action');
		
		// If there's a request for a single post
		if( $this->request->has('action') && is_numeric($this->request->get('action')) ){ 
				
			// How to translate URL in correct action variable?
			$postmodel = $this->_load('app')->model('postsModel', $locator->get('Db'));
			$content = $postmodel->find($this->request->get('action'));
			$template = $this->_load()->template('singlePost');
			$template->set('content', $content);
			
			$commentsmodel = $this->_load()->model('commentsModel', $locator->get('Db'));
			$comments = $commentsmodel->findByPost($this->request->get('action'));
			$template->set('comments', $comments);
			
			/* When comment form is posted */
			if ($this->request->isPost()) {
				if ($commentsmodel->isValid($this->request)) {
					$result = $commentsmodel->save($this->request);
					// return succesfull post message
					
				} else {
					// return error message
					
				}
			}
			
			$this->response->set('maincontent', $template);
			$this->response->set('subcontent','<p>This is the subcontent.</p>');

		} 
		else  // show all posts 
		{
			$model = $this->_load('app')->model('postsModel', $locator->get('Db'));
			$content = $model->listAll();
			$template = $this->_load()->template();
			$template->set('content', $content);
			$maincontent = $template->render();		

			$this->response->set('maincontent', $maincontent);
			$this->response->set('subcontent','<p>This is the subcontent.</p>');

		}
				
	}

}