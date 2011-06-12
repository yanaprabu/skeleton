<?php

class cart extends A_Controller_Action
{

	public function index($locator)
	{
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		$session = $locator->get('Session');
		$session->start();
		
		// load product model
		$product = $this->_load()->model('product');
		
		// get cart from session
		$cartsession = new A_Cart_Session($session);
		$cart = $cartsession->getInstance();

		// process cart params to add, delete, change items
		$cartrequest = new A_Cart_Request($cart);
		$cartrequest->processRequest();

		// get any items added so we can fetch associated product records
		$newitems = $cartrequest->getNewItems();
		
		// get SKUs for query
		$skus = array();
		foreach ($newitems as $item) {
			$skus[] = $item->getProductID();
		}
		// get all new product records in one query
		$rows = $product->findProductsSkus($skus);
		
		// assign product data to cart items for display
		foreach ($newitems as $item) {
			$sku = $item->getProductID();
			foreach ($rows as $row) {
				if ($row['sku'] == $sku) {
					$item->setId($row['id']);
					$item->setUnitPrice($row['price']);
					$item->setData('name', $row['name']);
					$item->setData('color', $row['color']);
					$item->setData('size', $row['size']);
					break;
				}
			}			
		}
		
		// add new items now that they have had product data assigned
		$cartrequest->addNewItems();

		$response->setPartial('content', 'cart', array('product'=>$product, 'cart'=>$cart, 'cartrequest'=>$cartrequest));
		
	}
}