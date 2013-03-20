<?php
/**
 * Mapper.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_Mapper
 *
 * Provides mapping from module/controller/action request variables to path/class/method
 *
 * @package A_Controller
 */
class A_Controller_Mapper
{

	protected $dir = '';
	protected $class = '';
	protected $method = '';
	protected $default_action;
	protected $base_path;					// path to controllers is base_path + path|default_dir
	protected $default_dir = '';
	protected $class_dir = 'controllers/';
	protected $file_extension = '.php';
	protected $class_transform = null;
	protected $class_prefix = '';
	protected $class_suffix = '';			// e.g. 'Controller';
	protected $method_transform = null;
	protected $method_prefix = '';
	protected $method_suffix = '';			// e.g. 'Action';
	protected $dir_param = 'module';
	protected $class_param = 'controller';
	protected $method_param = 'action';
	protected $default_method = 'index';

	public function __construct($base_path, $default_action)
	{
		$this->setBasePath($base_path);
		$this->default_action = $default_action;
	}

	public function setBasePath($path)
	{
		$this->base_path = $path ? rtrim($path, '/') . '/' : '';
		return $this;
	}

	public function setDefaultDir($dir='default')
	{
		$this->default_dir = $dir ? trim($dir, '/') . '/' : '';
		return $this;
	}

	public function setClassDir($dir)
	{
		$this->class_dir = $dir ? trim($dir, '/') . '/' : '';
		return $this;
	}

	public function setDir($dir)
	{
		if ($dir) {
			$this->dir = rtrim($dir, '/') . '/';		// paths have trailing slash
		} else {
			$this->dir = $this->default_dir;
		}
		return $this;
	}

	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}

	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	public function setDirParam($param)
	{
		$this->dir_param = $param;
		return $this;
	}

	public function setControllerParam($param)
	{
		$this->class_param = $param;
		return $this;
	}

	public function setActionParam($param)
	{
		$this->method_param = $param;
		return $this;
	}

	public function setDefaultMethod($default_method)
	{
		$this->default_method = $default_method;
		return $this;
	}

	public function getDefaultMethod()
	{
		return $this->default_method;
	}

	public function setDefaultAction($default_action)
	{
		$this->default_action = $default_action;
		return $this;
	}

	public function setClassNaming($class_prefix='', $class_transform=null, $class_suffix='')
	{
		$this->class_prefix = $class_prefix;
		$this->class_transform = $class_transform;
		$this->class_suffix = $class_suffix;
		return $this;
	}

	public function setMethodNaming($method_prefix='', $method_transform=null, $method_suffix='')
	{
		$this->method_prefix = $method_prefix;
		$this->method_transform = $method_transform;
		$this->method_suffix = $method_suffix;
		return $this;
	}

	public function getBasePath()
	{
		return $this->base_path;
	}

	public function getDir()
	{
		return $this->dir;
	}

	public function getClassDir()
	{
		return $this->class_dir;
	}

	public function getPath()
	{
		return $this->base_path . $this->dir . $this->class_dir;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Return array of paths to MVC type (e.g. controllers, models, views, helpers)
	 *
	 * @param string $type
	 * @return array
	 */
	public function getPaths($type)
	{
		$type = rtrim($type, '/') . '/';		// paths have training space
		$paths['app'] = $this->base_path;
		$paths['module'] = $paths['app'] . $this->dir;
		$paths['controller'] = $paths['module'] . $type . $this->class . '/';
		$paths['action'] = $paths['controller'] . ($this->method ? "$this->method/" : '');
		$paths['app'] .= $type;
		$paths['module'] .= $type;
		return $paths;
	}

	public function getFormattedClass()
	{
		$base = $this->class;
		if ($this->class_transform) {
			$base = call_user_func($this->class_transform, $base);
		}
		return $this->class_prefix . $base . $this->class_suffix;
	}

	public function getFormattedMethod()
	{
		$base = $this->method;
		if ($this->method_transform) {
			$base = call_user_func($this->method_transform, $base);
		}
		return $this->method_prefix . $base . $this->method_suffix;
	}

	/**
	 * Take a route array (or string or object) and set properties
	 *
	 * @param array $route
	 * @return $this
	 */
	public function setRoute($route)
	{
		if (!is_array($route)) {
			if (is_string($route)) {
				$route = explode('/', $route);
			// deal with route object that implment ArrayAccess
			} elseif (is_object($route) && is_a($route, 'ArrayAccess')) {
				$tmp = array();
				if (isset($route[0])) $tmp[0] = $route[0];
				if (isset($route[1])) $tmp[1] = $route[1];
				if (isset($route[2])) $tmp[2] = $route[2];
				$route = $tmp;
			// error
			} else {
				$route = array('', '', '');
			}
		}
		switch (count($route)) {
		case 2:							// "class/method"
			array_unshift($route, '');
			break;
		case 1:							// "class"
			array_unshift($route, '');
			$route[2] = $this->default_method;
			break;
		case 0:
			$route = array('', '', '');
		}
		$this->setDir($route[0]);
		$this->class = $route[1];
		$this->method = $route[2];
		return $this;
	}

	public function getRoute($request)
	{
		$regex = array('/^[^a-zA-Z0-9]*/', '/[^a-zA-Z0-9]*$/D', '/[^a-zA-Z0-9\_\-]/');
		$this->dir = preg_replace($regex, array(''), $request->get($this->dir_param));
		if ($this->dir) {
			$this->dir .= '/';		// paths have trailing slash
		} else {
			$this->dir = $this->default_dir;
		}
		$this->class = preg_replace($regex, array(''), $request->get($this->class_param));
		$this->method = preg_replace($regex, array(''), $request->get($this->method_param));

		$path = $this->getPath();
		if (!$this->class) {
			$this->setRoute($this->default_action);
		}
		$route = array($this->dir, $this->class, $this->method);
		if ($route[2] == '') {
			$route[2] = $this->default_method;
		}

		return $route;
	}

}
