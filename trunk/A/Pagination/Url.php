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

class A_Pagination_Url	{

	protected $base;
	protected $protocol;
	protected $state = array();

	/**
	 * @param 
	 * @type 
	 */
	public function __construct ($base = '', $protocol = 'http')	{
		$this->base = $base;
		$this->protocol = $protocol;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function set ($key, $value)	{
		$this->state[$key] = $value;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function setBase ($base)	{
		$this->base = $base;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function setProtocol ($protocol)	{
		$this->protocol = $protocol;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function render ($page = false, $params = array())	{
		$params = array_merge ($this->state, $params);
		$base = $this->base ? $this->protocol . '://' . $this->base . '/' : '';
		$page = $page ? $page : $_SERVER['SCRIPT_NAME'];
		$query = count ($params) > 0 ? '?' . http_build_query ($params) : '';
		return $base . $page . $query;
	}

}