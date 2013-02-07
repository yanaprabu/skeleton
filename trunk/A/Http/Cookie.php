<?php

/**
 * Url.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Cookie
 *
 * This class provides various methods with which to create, manipulate, and read URLs.
 *
 * @package A_Http
 */
class A_Http_Cookie
{
	protected $expire = 0;
	protected $path = '';
	protected $domain = '';
	protected $secure = false;
	protected $httponly = false;

	public function get($name)
	{
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}

	public function set($name, $value, $expire=null, $path=null, $domain=null, $secure=null, $httponly=null)
	{
		// if not set then use defaults
		if ($expire === null) {
			$expire = $this->expire;
		}
		if ($path === null) {
			$path = $this->path;
		}
		if ($domain === null) {
			$domain = $this->domain;
		}
		if ($secure === null) {
			$secure = $this->secure;
		}
		if ($httponly === null) {
			$httponly = $this->httponly;
		}
		// save cookie data
		$this->cookies[$name] = array(
			'name' => $name,
			'value' => $value,
			'expire' => $expire,
			'path' => $path,
			'domain' => $domain,
			'secure' => $secure,
			'httponly' => $httponly,
			);
		return $this;
	}

	/**
	 * Import an array of cookies.  For the proper array values, see the argument names of the setcookie() method.
	 *
	 * http://php.net/setcookie
	 *
	 * The array key can be used instead of the 'name' key.
	 *
	 * @param array $cookies
	 * @return $this
	 */
	public function import($cookies)
	{
		foreach ($cookies as $name => $cookie) {
			$this->cookies[$name] = array(
				'name' => isset($cookie['name']) ? $cookie['name'] : $name,
				'value' => isset($cookie['value']) ? $cookie['value'] : null,
				'expire' => isset($cookie['expire']) ? $cookie['expire'] : $this->expire,
				'path' => isset($cookie['path']) ? $cookie['path'] : $this->path,
				'domain' => isset($cookie['domain']) ? $cookie['domain'] : $this->domain,
				'secure' => isset($cookie['secure']) ? $cookie['secure'] : $this->secure,
				'httponly' => isset($cookie['httponly']) ? $cookie['httponly'] : $this->httponly
			);
		}
		return $this;
	}

	public function setExpire($expire)
	{
		$this->expire = $expire;
		return $this;
	}

	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	public function setDomain($domain)
	{
		$this->domain = $domain;
		return $this;
	}

	public function setSecure($secure)
	{
		$this->secure = $secure;
		return $this;
	}

	public function setHttponly($httponly)
	{
		$this->httponly = $httponly;
		return $this;
	}

	public function render()
	{
		foreach ($this->cookies as $c) {
			setcookie($c['name'], $c['value'], $c['expire'], $c['path'], $c['domain'], $c['secure'], $c['httponly']);
        }
	}

	public function __toString()
	{
		return $this->render();
	}

}
