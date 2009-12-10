<?php
/**
 * A_Pagination_
 *
 * Generate URLs
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

class A_Pagination_Helper_Url	{

	protected $base;
	protected $protocol;
	protected $state = array();

	/**
	 * @param $base string domain name and path
	 * @param $protocol string http or https
	 */
	public function __construct ($base = '', $protocol = 'http')	{
		$this->base = $base ? $base : $_SERVER['SERVER_NAME'];
		$this->protocol = $protocol;
	}

	/**
	 * @param $key string
	 * @param $value string
	 * @return $this for fluent interface
	 */
	public function set ($key, $value)	{
		$this->state[$key] = $value;
		return $this;
	}

	/**
	 * @param $base string domain name and path
	 * @return $this for fluent interface
	 */
	public function setBase ($base)	{
		$this->base = $base;
		return $this;
	}

	/**
	 * @param $protocol string http or https
	 * @return $this for fluent interface
	 */
	public function setProtocol ($protocol)	{
		$this->protocol = $protocol;
		return $this;
	}

	/**
	 * @param $page string specific script name
	 * @param $params array of name value pairs where keys are name
	 * @param $ignore array of param names to remove
	 * @return string full URL
	 */
	public function render ($page = false, $params = array(), $ignore = array())	{
		$params = array_merge ($this->state, $params);
		foreach ($ignore as $key) unset ($params[$key]);
		$base = $this->base ? $this->protocol . '://' . $this->base . '/' : '';
		$page = $page ? $page : $_SERVER['PHP_SELF'];
		$query = '';
		if (count($params) > 0) {
			$query =  (strpos($page, '?') === false) ? '?' : '&';
			$query .=  http_build_query($params);
		}
		return $base . $page . $query;
	}

}