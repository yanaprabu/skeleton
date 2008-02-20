<?php

class home extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action. Shows latest articles */
	function run($locator) {
		/* Get latest posts and articles */
		$model = $this->load()->model('articlesModel');
		$content = $model->listAll();
		$postsmodel = $this->load()->model('postsModel');
		$postscontent = $postsmodel->listAll();

		$template = $this->load()->template('home');
		$template->set('articles', $content);
		$template->set('posts', $postscontent);
		$this->response->set('layout','homelayout');
		$this->response->setRenderer($template);
		
	}

}