<?php
/**
 * Request.php
 *
 * @package  A_Cli
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Cli_Request
 * 
 * Encapsulate the HTTP request in a class to access information and values
 */
class A_Cli_Request
{

	public $data = array();
	protected $method = false;
	protected $script_name = false;
	protected $filters = array();
	
	public function __construct()
	{
		// check if called from CLI
		if (isset($_SERVER['argv'])) {
			$this->method = 'CLI';
			// first arg is SCRIPT_NAME
			$this->script_name = $_SERVER['argv'][0];
			// save the rest of the args
			$this->data = $_SERVER['argv'];
		}
		if (isset($this->data[1])) {
			// check if first arg is path
			if (strstr($this->data[1], '=') === false) {
				$this->data['PATH_INFO'] = trim($this->data[1], '/');
			}
			foreach ($this->data as $arg) {
				// is it a HTTP style name=value style arg
				if (strstr($arg, '=') !== false) {
					$data = array();
					parse_str($arg, $data);	// parse arg and add to data
					$this->data = array_merge($this->data, $data);
				}
			}
		}		
	}
	
	public function setPathInfo($path_info)
	{
		$this->data['PATH_INFO'] = trim($path_info, '/');
		return $this;
	}
	
	public function getScriptName()
	{
		return $this->script_name;
	}
	
	public function getFilters()
	{
		return $this->filters;
	}
	
	public function setFilters($filters)
	{
		$this->filters = is_array($filters) ? $filters : array($filters);
		return $this;
	}
	
	public function getMethod()
	{
		return $this->method;
	}
	
	public function isCli()
	{
		return $this->method == 'CLI';
	}
	
	protected function _get(&$data, $name, $filters=null, $default=null)
	{
		if (isset($data[$name])) {
			if ($filters || $this->filters) {
				// allow single filter to be passed - convert to array
				if (!is_array($filters)) {
					$filters = array($filters);
				}
				// if global filters - merge
				if ($this->filters) {
					$filters = array_merge($this->filters, $filters);
				}
				// allow parameter that is array
				$d = is_array($data[$name]) ? $data[$name] : array($data[$name]);
				foreach (array_keys($d) as $key) {
					foreach ($filters as $filter) {
						if (is_string($filter)) {
							if (substr($filter, 0, 1) == '/') {
								$d[$key] = preg_replace($filter, '', $d[$key]);
							} else {
								$d[$key] = $filter($d[$key]);
							}
						} elseif (is_object($filter)) {
							$d[$key] = $filter->doFilter($d[$key]);
						} elseif (is_array($filter)) {
							$d[$key] = call_user_func($filter, $d[$key]);
						}
					}
				}
				// if orignial param was array return array else revert to scalar
				if (is_array($data[$name])) {
					return $d;
				} else {
					return $d[0];
				}
			}
			return $data[$name];
		} elseif ($default !== null) {
			return $default;
		}
	}
	
	public function get($name, $filter=null, $default=null)
	{
		return $this->_get($this->data, $name, $filter, $default);
	}
	
	public function export($filter=null, $pattern=null)
	{
		if ($filter || $pattern) {
			$export = array();
			foreach (array_keys($this->data) as $key) {
				if (preg_match($pattern, $key)) {
					$export[$key] = $this->_get($this->data, $key, $filter);
				}
			}
			return $export;
		} else {
			return $this->data;
		}
	}
	
	public function set($name, $value, $default=null)
	{
		if ($value !== null) {
			$this->data[$name] = $value;
		} elseif ($default !== null) {
			$this->data[$name] = $default;
		} else {
			unset($this->data[$name]);
		}
		return $this;
	}
	
	public function has($name)
	{
		return isset($this->data[$name]);
	}

}
