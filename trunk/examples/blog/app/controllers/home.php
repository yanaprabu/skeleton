<?php

class home extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action. Shows latest articles */
	function run($locator) {
		/* Get latest posts to show on homepage */
		$postsmodel = $this->load()->model('postsModel');
		$postscontent = $postsmodel->listAll();
		
		$maincontent = '<ul>';
		foreach ($postscontent as $post){ 
			$maincontent .= '<li>';
			$maincontent .=  '<h4><a href="'. $post['permalink'] . '">' . $post['title'] . '</a></h4>';
			$maincontent .=  '<p>' .  $post['date'] . '</p>';
			$maincontent .=  '<p>' .  $post['excerpt'] . '</p>';
			$maincontent .=  '<p>' .  $post['content'] . '</p>';
			$maincontent .=  '</li>';
		}
		$maincontent .= '</ul>';
		$subcontent = 'This is the subcontent of the homepage';
		
		$this->response->set('layout', 'homelayout');
		$this->response->set('maincontent', $maincontent);
		$this->response->set('subcontent', $subcontent);
	}

}