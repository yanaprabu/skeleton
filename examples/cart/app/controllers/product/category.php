<?php

class product_category extends A_Controller_Action
{

	public function index($locator)
	{
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$product = $this->_load()->model('product');
		
		$response->setPartial('content', 'product/category', array('product'=>$product));
		
	}
}