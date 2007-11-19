<?php

class A_Controller_Mvc {
protected $model;
protected $view;
    
	public function __construct() {
	}
    
	public public function process($locator) {
		if(isset($this->model)) {
			$this->model->run($locator);
		}
		if(isset($this->view)) {
			$this->view->render($this->model);
		}
    }
}
