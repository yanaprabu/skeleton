<?php

/**
 * Description of CachedController
 *
 * @author jonah
 */
class A_Socket_EventListener_CachedController
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
	{

	}
}
