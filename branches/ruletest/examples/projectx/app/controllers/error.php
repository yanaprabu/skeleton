<?php

class error extends A_Controller_Action
{
	
	public function index($locator)
	{
		$view = $this->_load()->view();
		$this->_response()->set('content', $view);
	}

}