<?php

class example extends A_Controller_Action {

	function index($locator) {
		$view = $this->_load()->view();
		$view->set('title', 'This is the title set in the controller');	
		$view->set('colors', array(
								array('id'=>'1', 'color'=>'Red'), 
								array('id'=>'2', 'color'=>'Blue'), 
								array('id'=>'3', 'color'=>'Green'), 
								));	
		echo $view->render();
	}
	
	function simpletemplate($locator) {
		$view = $this->_load()->view();
		$view->set('title', 'This is the title set in the controller');	
		echo $view->render();
	}
	
	function needsdifferenttemplate($locator) {
		$view = $this->_load()->view();
		$view->set('title', 'This is the needsdifferenttemplate title set in the controller');	
		$view->set('content', 'This is the content set in the action needsdifferenttemplate');
		$view->setTemplate('differenttemplate');
		
		echo $view->render();
	}
	
	function actionsetscontent($locator) {
		$view = $this->_load()->view();
		//$view = new A_Http_View();
		$view->setTemplate('');
		$view->setContent('This is the content set in the action actionsetscontent');	//dump($view);
		echo $view->render();	
	}
	
	function actionsetsrenderer($locator) {
		//$view = $this->_load()->view();
		
		$template = $this->_load()->template('setsrenderertemplate.html');
		$template->set('content', 'foo');//dump($template);
		
		$view = new A_Http_View();
		$view->setRenderer($template);//dump($view);	
		echo $view->render();	
	}	

}
