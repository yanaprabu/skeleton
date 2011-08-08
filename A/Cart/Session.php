<?php
/**
 * Session.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cart_Session
 * 
 * Session-based storage for shopping cart
 * 
 * @package A_Cart
 */
class A_Cart_Session
{
	protected $session_name = 'A_Cart';
	
	/**
	 * @param string $name
	 * @return A_Cart_Manager
	 */
	public function __construct($session=null)
	{
		if ($session) {
			$this->session = $session;
		} else {
			$this->session = new A_Session();
		}
	}
	
	/**
	 * @param string $name
	 * @return A_Cart_Manager
	 */
	public function getInstance($name='cart')
	{
		$this->session->start();
		$data =& $this->session->getRef($this->session_name);
		if (isset($data[$name])) {
			$cart = $data[$name];
		} else {
			$cart = new A_Cart_Manager($name);
			$data[$name] = $cart;
		}
		return $cart;
	}
	
	/**
	 * @param string $name
	 */
	public function destroy($name='cart')
	{
		$this->session->start();
		$this->session->set($name, null);
	}

}
