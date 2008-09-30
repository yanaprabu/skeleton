<?php
/**
 * Parse PATH_INFO and set Request object with values
 *
 * @package A_Http
 *

This class maps PATH_INFO values onto request vars using an array that describes
the mappings. The array in in the format of the following example:

$map = array(
	'' => array(		// '' is the route array used when no match is found
		'controller',		// position in PATH_INFO and requestprotected to map onto
		'action',
		'id',
		),
	'date' => array(	// if 'date' is found in the first element of PATH_INFO use the map below
		'' => array(
			'controller',
			'year',
			'month',
			'day',
			),
		),
	);
	
The mapper starts at the root level of the array and searches keys for a match on the first
element in the PATH_INFO. If a key match is found it then uses the array under that key to search
the next element in PATH_INFO. If no match is found then the [''] index in the array is used. 
Every array should have a [''] key containing the route array to use to map PATH_INFO to Request vars. 

In the example above, the PATH_INFO of "/view/person/Bob/" will map to the Request as 
"?controller=view&action=person&id=Bob" because 'view' does not match a key in the root array 
of the map, in this map that is '' and 'date'. The [''] array at the level where no match was found 
is used for the mapping. The PATH_INFO of "/date/2006/January/1st/" will map to the Request
as "?controller=date&year=2006&month=January&day=1st" because 'date' matches a key in the map.

*Additional parameters in the PATH_INFO past those defined in the map with be combined in pairs
to request vars. For example, /view/person/Bob/age/42/height/84/ will map the the Reqest as
"?controller=view&action=person&id=Bob&age=42&height=84". This can be turned off with the
$map_extra_param_pairs parameter. 

The position where mapping stopped is saves so additional maps can be applied to PATH_INFO. For
example, the Front Http might map the first two values in PATH_INFO to the default
/controller/action/ parameters. Different dispatched controllers may then set their own maps
to map the parameters in PATH_INFO starting at the third parameter. 
*/
class A_Http_PathInfo {
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

    public function __construct($map=null, $map_extra_param_pairs=true) {
    	if ($map != null) {
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
	        // fix by thinsoldier for NT servers
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
	
	public function setScriptExtension($script_extension) {
		$this->script_extension = $script_extension;
		return $this;
	}

	public function setPath($path) {
		$this->path = $path;
		return $this;
	}

	public function setMap($map) {
		$this->map = $map;
		return $this;
	}

	public function addMap($map) {
		$this->map = array_merge($this->map, $map);
		return $this;
	}

	public function run($request) {
#        if ($this->path) {
// search map for route
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
		        			++$i;
        					break;	// out of while
        				}
        			}
        			++$i;
        		}
				       		
// assign parameters based on route
				if (isset($map[''])) {
					$route = $map[''];
	        		$route_size = count($route);
					for ($i=$this->path_pos, $j=0; $j<$route_size; ++$i, ++$j) {
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
						++$i;
	        		}
	        		foreach ($params as $param => $value) {
						$request->set($param, $value);
					}
				}
        	}
        }
#	}
}
