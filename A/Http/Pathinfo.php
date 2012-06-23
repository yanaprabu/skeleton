<?php
/**
 * Pathinfo.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Pathinfo
 *
 * Parse PATH_INFO based on mapped routes and set the Request object with values
 *
 * @package A_Http
 */
class A_Http_Pathinfo
{

	protected $map = array(
		'' => array(
			'controller',
			'action',
		),
	);
	protected $map_extra_param_pairs;
	protected $path;
	protected $path_pos;		// the position in path_info after the end of the current route
	protected $script_extension = '.php';

	public function __construct($map=null, $map_extra_param_pairs=true)
	{
		if ($map !== null) {
			$this->map = $map;
		}
		$this->map_extra_param_pairs = $map_extra_param_pairs;

		if (isset($_SERVER['PATH_INFO'])) {
			$path = $_SERVER['PATH_INFO'];
		} else {
			$path = $_SERVER['REQUEST_URI'];
			if (strpos($path, $this->script_extension) !== FALSE) {
				$base = $_SERVER['SCRIPT_NAME'];			// using script name
			} else {
				$base = dirname($_SERVER['SCRIPT_NAME']);		// using rewrite rules
			}
			if ($base != '/' && $base != '\\') {
				$len = strlen($base) + 1;
				$path = substr($path, $len);
			}
			if (strstr($path, '?')) {
				$path = substr($path, 0, strpos($path, '?'));
			}
		}
		$this->path = trim($path, '/\\');
		$this->path_pos = 0;
	}

	public function setScriptExtension($script_extension)
	{
		$this->script_extension = $script_extension;
		return $this;
	}

	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	public function setMap($map)
	{
		$this->map = $map;
		return $this;
	}

	public function addMap($map)
	{
		$this->map = array_merge($this->map, $map);
		return $this;
	}

	public function getMap()
	{
		return $this->map;
	}

	public function addRoute($route, $params)
	{
		$map =& $this->map;		// get reference that we can set to each level in the map as we assign
		if (is_string($route)) {
			if ($route != '') {
				$route = explode('/', trim($route, '/'));
			} else {
				$route = array('');
			}
		}
		$last = count($route) - 1;
		for ($i = 0; $i <= $last; $i++) {
			if (!isset($map[$route[$i]])) {
				if ($i == $last) {
					$map[$route[$i]][''] = $params;
				} else {
					$map[$route[$i]] = array(''=>array());
					$map =& $map[$route[$i]];
				}
			}
		}
		$map[''] = $params;
		return $this;
	}

	public function run($request)
	{
		$request->set('PATH_INFO', $this->path);
		if ($this->map) {
			$path_info = explode('/', $this->path);
			$i = $this->path_pos;			// start a previous position
			$path_info_size = count($path_info);
			$map =& $this->map;
			while ($i < $path_info_size) {
				$value = $path_info[$i];
				if ($value) {
					$match = false;
					foreach (array_keys($map) as $key) {
						if (($key == $value) || ((substr($value, 0, 1) == '/') && preg_match($key, $value))) {
							$match = true;
							break;	// out of foreach
						}
					}
					if ($match) {
						$map =& $map[$value];
					} else {
						$i++;
						break;	// out of while
					}
				}
				$i++;
			}

			// assign parameters based on route
			if (isset($map[''])) {
				$route = $map[''];
				$route_size = count($route);
				for ($i=$this->path_pos, $j=0; $j<$route_size; $i++,$j++) {
					if (!isset($path_info[$i])) {
						$path_info[$i] = '';
					}
					if (is_array($route[$j])) {
						if (isset($route[$j]['replace']) && $route[$j]['replace']) {
							if (is_array($route[$j]['replace'])) {
								foreach ($route[$j]['replace'] as $name => $value) {
									$request->set($name, $value);
								}
							} else {
								$request->set($route[$j]['name'], $route[$j]['replace']);
							}
						} elseif ($path_info[$i] == '' && isset($route[$j]['default'])&& $route[$j]['default']) {
							$request->set($route[$j]['name'], $route[$j]['default']);
						} else {
							$request->set($route[$j]['name'], $path_info[$i]);
						}
						if (isset($route[$j]['stop'])) {
							break;
						}
					} else {
						$request->set($route[$j], $path_info[$i]);
					}
				}
				$this->path_pos = $i;	// save position so route can be re-run
			}
			// assign extra, unmapped parameter pairs
			if ($this->map_extra_param_pairs) {
				$params = array();
				while ($i < $path_info_size) {
					$param = isset($path_info[$i]) ? $path_info[$i] : null;
					if (++$i < $path_info_size) {
						$value = isset($path_info[$i]) ? $path_info[$i] : null;
						if ($param !== null) {
							// if values is already set then we have multiple values of the same name
							if (isset($params[$param])) {
								// if already an array then append
								if (is_array($params[$param])) {
									$params[$param][] = $value;
								} else {
									// if not an array then make one with current and new values
									$params[$param] = array($params[$param], $value);
								}
							} else {
								$params[$param] = $value;
							}
						}
					}
					$i++;
				}
				foreach ($params as $param => $value) {
					$request->set($param, $value);
				}
			}
		}
	}

}
