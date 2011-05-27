<?php
/**
 * A_Model_Helper_Load
 * 
 * Provides class loading and instantiation within the application directory
 * 
 * @package A_Model
 * @subpackage Helper
 */

class A_Model_Helper_Load {
	protected $locator;
	protected $parent;
	protected $paths = array(
							'app'=>'', 
							'module'=>'', 
							'controller'=>'', 
							'action'=>'',
							);
	protected $dirs = array(
							'model'=>'models/', 
							'helper'=>'helper/', 
							'lib'=>'libs/',
							);
	protected $suffix = array(
							'model'=>'Model', 
							'helper'=>'Helper', 
							'lib'=>'', 
							);
	protected $scope;
	protected $scopePath;
	protected $renderClass = 'A_Template_Include';
	protected $errorMsg = array();
	
	public function __construct($locator, $parent, $scope=null){
		$this->locator = $locator;
		if ($locator) {
			$mapper = $locator->get('Mapper');
			if ($mapper) {
				$this->setMapper($mapper);
			} else {
				$this->errorMsg[] =  "No Mapper to provide paths. ";
			}
		} else {
			$this->errorMsg[] =  "No Locator: Model constructor may not be calling self::__construct($locator). ";
		}
		$this->parent = $parent;
		$this->load($scope);
	}
	 
	/*
	 * Scopes are:
	 * global /app/
	 * module /app/$module/
	 * controller /app/$module/$type/$controller/
	 * action /app/$module/$type/$controller/$action/
	 */
	public function setMapper($mapper){
		if ($mapper) {
			$this->action = $mapper->getClass();
			$this->method = $mapper->getMethod();
			$this->paths = $mapper->getPaths('%s');	// get paths array with sprintf placeholder
		}
		return $this;
	}
		
	public function setPath($name, $path, $relative_name=''){
		$path = $path ? (rtrim($path, '/') . '/') : '';		// add trailing dir separator
		if ($relative_name) {
			$this->paths[$name] = $this->paths[$relative_name] . $path;
		} else {
			$this->paths[$name] = $path;
		}
		return $this;
	}
	
	protected function setDir($name, $dir){
		$this->dirs[$name] = $dir ? (rtrim($dir, '/') . '/') : '';
		return $this;
	}
	
	public function setSuffix($name, $suffix){
		$this->suffix[$name] = $suffix;
		return $this;
	}
	
	/**
	 * get error messages
	 */
	public function getErrorMsg($separator="\n") {
		if ($separator) {
			return implode($separator, $this->errorMsg);
		}
		return $this->errorMsg;
	}
	
	public function load($scope=null, $target=null) {
		if (is_array($scope)) {
			$scope = $scope[0];
		}
		if (! isset($this->paths[$scope])) {
			$scope = 'module';	 // the default setting e.g., "/app/module/models"
		}
		$this->scope = $scope;
		$this->scopePath = $this->paths[$scope];
		return $this;
	}

	public function __call($type, $args) {
		$obj = null;
		// is this a defined type of subdirectory
		if (isset($this->dirs[$type])) {
			// insert type path into scope path
			if ($this->scopePath) { 
				$path = str_replace('%s', $this->dirs[$type], $this->scopePath);
			} else {
				$path = $this->dirs[$type];		// just in case no scopePath
			}
			
			// helpers take a parent instance as the parameter
			if ($type == 'helper') {
				$args[1] = $this->parent;
			}
			
			if ($type == 'lib') {
				// lookup the renderer by extension, if given
				$path_parts = pathinfo($class);
				// if dir in name the add to path
				if ($path_parts['dirname'] != '.') {
					$path .= trim($path_parts['dirname'], '/') . '/';
				}
				$class = $path_parts['basename'];
			}
			if ($this->locator) {
				if ($this->locator->loadClass($class, $path)) { // load class if necessary
					$obj = new $class(isset($args[1]) ? $args[1] : $this->locator);
				} else {
					$this->errorMsg[] = "\$this->_load('{$this->scope}')->$type(" . (isset($args[0]) ? "'{$args[0]}'" : '') . ") call to Locator->loadClass('$class', '$path') failed. Check scope, path and class name. ";
				}
			} elseif (file_exists("$path$class.php")) {
				#include_once "$path$class.php";
				if (class_exists($class)) {
					$obj = new $class(isset($args[1]) ? $args[1] : $this->locator);
				}
			} else {
				$this->errorMsg[] =  "Could not load $path$class.php. ";
			}
			// initialize object
			if ($obj) {
			} else {
				$this->errorMsg[] = "Did not create $class object. ";
			}
			//reset scope and response
			$this->scope = null;
			$this->scopePath = null;
			return $obj;
		}
	}

}
