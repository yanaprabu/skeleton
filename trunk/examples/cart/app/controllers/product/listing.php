<?php

class product_listing extends A_Controller_Action
{

	public function index($locator)
	{
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$product = $this->_load()->model('product');
		
		$response->setPartial('content', 'product/listing', array('product'=>$product, 'category'=>$request->get('category', '/[^A-Za-z0-9]/')));
		
	}
}