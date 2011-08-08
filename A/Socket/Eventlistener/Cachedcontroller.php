<?php
/**
 * Cachedcontroller.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_Eventlistener_Cachedcontroller
 * 
 * Stores controllers in memory ahead of time to reduce routing time.
 * 
 * @package A_Socket
 */
class A_Socket_Eventlistener_Cachedcontroller
{

	public function  __construct($locator)
	{
		parent::__construct($locator);
	}
	
	public function onConnect($data)
	{
		$data->setSession(new A_Collection());
	}
	
	public function onDisconnect($data)
	{

	}
	
	public function onMessage($data)
	{
		$route = $data->getRoute();
		
		// check validity of route and cached controller/action
		if (is_string($route) && isset($this->_cache[$route]) && is_callable(array())) {
			
		}
	}
	
	/**
	 *
	 * @param array $cache Cache of controller/action routes mapped to keywords
	 */
	public function setCache($cache)
	{}

}
