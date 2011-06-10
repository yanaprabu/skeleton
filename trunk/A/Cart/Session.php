<?php
/**
 * Session.php
 *
 * @package  A_Cart
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Cart_Session
 * 
 * Session-based storage for shopping cart
 */
class A_Cart_Session
{
	
	/**
	 * @param string $name
	 * @return A_Cart_Manager
	 */
	public function getInstance($name='cart')
	{
		$session = new A_Session('A_Cart');
		$session->start();
		if (! isset($_SESSION['A_Cart'][$name])) {
			$_SESSION['A_Cart'][$name] = new A_Cart_Manager($name);
		}
		$cart = $_SESSION['A_Cart'][$name];
		return $cart;
	}
	
	/**
	 * @param string $name
	 */
	public function destroy($name='cart')
	{
		$session = new A_Session('A_Cart');
		$session->start();
		unset($_SESSION['A_Cart'][$name]);
	}

}
