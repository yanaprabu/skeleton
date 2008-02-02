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
		// Set the layout you want for the main content
		$this->response->set('layout','articlelayout');
		
		// Load model data
		$model = $this->load()->model('articlesModel');
		$content = $model->listAll();
		$template = $this->load()->template();
		$template->set('articles', $content);
		$maincontent = $template->render(); //dump($maincontent);
		
		// Now give the main content to the response as 'maincontent'
		$this->response->set('maincontent',$maincontent);
		$this->response->set('subcontent','This is the subcontent');
	//	$this->response->setRenderer('articlelayout');
	//	$this->load()->response('rightcol')->template('sidebar');
		
	//	$this->load()->response('maincontent')->set('maincontent','nu is dit de main cont');
		/*
		$searchbox = $this->load()->template('searchbox');dump($searchbox);
		$searchbox->set('label','Mylabel');
		$content = $searchbox->render();//dump($content);
		$this->response->setContent($content);
		*/
		
	 	//$this->load()->response('maincontent')->template('bartemplate');
        //$this->load()->response('rightcol')->template('sidebar');

	}
	
}