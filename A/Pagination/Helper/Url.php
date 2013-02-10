<?php
/**
 * Url.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman, Christopher Thompson
 */

/**
 * A_Pagination_Helper_Url
 *
 * Generate URLs
 *
 * @package A_Pagination
 */
class A_Pagination_Helper_Url
{

	protected $base;
	protected $protocol;
	protected $state = array();

	/**
	 * @param string $base Domain name and path
	 * @param string $protocol HTTP or HTTPS
	 */
	public function __construct($base='', $protocol='http')
	{
		$this->base = $base ? $base : $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		$this->protocol = $protocol;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function set($key, $value)
	{
		$this->state[$key] = $value;
		return $this;
	}

	/**
	 * Get a state value
	 *
	 * @param string $key
	 * @param value $default Value returned if $key doesn't exist
	 * @return mixed
	 */
	public function get($key, $default=null)
	{
		return isset($this->state[$key]) ? $this->state[$key] : $default;
	}

	/**
	 * Import state data
	 *
	 * @param array $data
	 * @return $this
	 */
	public function import($data)
	{
		$this->state = array_merge($data, $this->state);
		return $this;
	}

	/**
	 * @param string $base Domain name and path
	 * @return $this
	 */
	public function setBase($base)
	{
		$this->base = rtrim(preg_replace('/^http.?:\/\//', '', $base), '/');
		return $this;
	}

	/**
	 * @param string $protocol HTTP or HTTPS
	 * @return $this
	 */
	public function setProtocol($protocol)
	{
		$this->protocol = $protocol;
		return $this;
	}

	/**
	 * @param string $page Specific script name
	 * @param array $params Name/value pairs where keys are name
	 * @param array $ignore Param names to remove
	 * @return string Full URL
	 */
	public function render($base='', $params=array(), $ignore=array())
	{
		$params = array_merge($this->state, $params);
		foreach ($ignore as $key)
			unset($params[$key]);
		if ($base == '') {
			$base = $this->base ? $this->protocol . '://' . $this->base : '';
		}
		$query = '';
		if (count($params) > 0) {
			$query = (strpos($base, '?') === false) ? '?' : '&';
			$query .= http_build_query($params);
		}
		return $base . $query;
	}

	/**
	 * Magic method to automatically render when used in a string context
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

}
