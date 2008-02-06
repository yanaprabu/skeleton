<?php
include_once 'A/DL.php';

class A_Controller_Mapper
{
	protected $dir = '';
	protected $class = '';
	protected $method = '';
	public $default_action;
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
	public $default_method = 'run';
	protected $map;

	public function __construct($base_path, $default_action) {
		$this->setBasePath($base_path);
		$this->default_action = $default_action;
	}

	public function setMap($map) {
		$this->map = $map;
		return $this;
	}

	public function setBasePath($path) {
		$this->base_path = $path ? rtrim($path, '/') . '/' : '';
		return $this;
	}

	public function setDefaultDir($dir='default') {
		$this->default_dir = $dir ? trim($dir, '/') . '/' : '';
		return $this;
	}

	public function setClassDir($dir) {
		$this->class_dir = $dir ? trim($dir, '/') . '/' : '';
		return $this;
	}

	public function setDirParam($param) {
		$this->dir_param = $param;
		return $this;
	}

	public function setControllerParam($param) {
		$this->class_param = $param;
		return $this;
	}

	public function setActionParam($param) {
		$this->method_param = $param;
		return $this;
	}

	public function setDefaultMethod($default_method) {
		$this->default_method = $default_method;
		return $this;
	}

	public function setDefaultAction($default_action) {
		$this->default_action = $default_action;
		return $this;
	}

	public function setNaming($class_prefix='', $class_suffix='', $method_prefix='', $method_suffix='') {
		$this->class_prefix = $class_prefix;
		$this->class_suffix = $class_suffix;
		$this->method_prefix = $method_prefix;
		$this->method_suffix = $method_suffix;
		return $this;
	}

	public function getBasePath() {
		return $this->base_path;
	}

	public function getDir() {
		return $this->dir;
	}

	public function getClassDir() {
		return $this->class_dir;
	}

	public function getClass() {
		return $this->class;
	}

	public function getPath() {
		return $this->base_path . $this->dir . $this->class_dir;
#		return $this->base_path . ($this->dir ? $this->dir : $this->default_dir) . $this->class_dir;
	}

	public function buildClass($base) {
		if ($this->class_transform) {
			$base = call_user_func($this->class_transform, $base);
		}
		return $this->class_prefix . $base . $this->class_suffix;
	}

	public function buildMethod($base) {
		if ($this->method_transform) {
			$base = call_user_func($this->method_transform, $base);
		}
		return $this->method_prefix . $base . $this->method_suffix;
	}

	public function doMapping($locator) {
		$request = $locator->get('Request');

		$regex = array('/^[^a-zA-Z0-9]*/', '/[^a-zA-Z0-9]*$/', '/[^a-zA-Z0-9\_\-]/');
		$this->dir = preg_replace($regex, array(''), $request->get($this->dir_param));
		if ($this->dir) {
			$this->dir .= '/';		// paths have trailing slash
		} else {
			$this->dir = $this->default_dir;
		}
		$this->class = preg_replace($regex, array(''), $request->get($this->class_param));
		$this->method = preg_replace($regex, array(''), $request->get($this->method_param));
		
		$path = $this->getPath();
		if ($this->class) {
			$action = new A_DL($path, $this->class, $this->method, array());
		} else {
			$action = $this->default_action;
			$action->dir = $path;
			$this->class = $action->class;
		}
		if ($action->method == '') {
			$action->method = $this->default_method;
		}

		return $action;
	}

}
