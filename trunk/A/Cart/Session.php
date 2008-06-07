<?php
include_once 'A/Session.php';
include_once 'A/Cart/Manager.php';

class A_Cart_Session {

	public function getInstance($name='cart') {
		$session = new A_Session('A_Cart');
		$session->start();
		if (! isset($_SESSION['A_Cart'][$name])) {
			$_SESSION['A_Cart'][$name] = new A_Cart_Manager($name);
		}
		$cart = $_SESSION['A_Cart'][$name];
		return $cart;
	}
	
	public function destroy($name='cart') {
		$session = new A_Session('A_Cart');
		$session->start();
		unset($_SESSION['A_Cart'][$name]);
	}

}
