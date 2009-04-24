<?php
/**
 * A_Controller_Helper_Load
 * 
 * Provides class loading and instantiation within the application directory
 * 
 * @package A_Controller
 */

class A_Controller_Helper_Load {
	protected $locator;
	protected $parent;
	protected $paths = array('app'=>'', 'module'=>'', 'controller'=>'', 'action'=>'');
	protected $dirs = array('helper'=>'helpers/', 'model'=>'models/', 'view'=>'views/', 'template'=>'templates/', );
	protected $action = null;
	protected $method = null;
	protected $suffix = array('model'=>'Model', 'view'=>'View', 'helper'=>'Helper');
	protected $rendererTypes = array('view', 'template');
	protected $scope;
	protected $scopePath;
	protected $responseName = '';
	protected $renderClasses = array(
								'php' => 'A_Template_Include',
								'html' => 'A_Template_Strreplace',
								'txt' => 'A_Template_Strreplace',
								);
	protected $renderClass = 'A_Template_Include';
	protected $renderExtension = 'php';
	protected $responseSet = false;
	protected $errorMsg = '';
	
	public function __construct($locator, $parent, $scope=null){
		$this->locator = $locator;
		if ($locator) {
			$mapper = $locator->get('Mapper');
			if ($mapper) {
				$this->setMapper($mapper);
			}
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
			$type = '%s';
			$this->action = $mapper->getClass();
			$this->method = $mapper->getMethod();
			$this->paths['app'] = $mapper->getBasePath();
			$this->paths['module'] = $this->paths['app'] . $mapper->getDir();
			$this->paths['controller'] = $this->paths['module'] . $type . $this->action . '/';// . $mapper->getClassDir();
			$this->paths['action'] = $this->paths['controller'] . ($this->method ? "$this->method/" : '');
			$this->paths['app'] .= $type;
			$this->paths['module'] .= $type;
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
	
	public function setRenderClass($name, $ext='php'){
		$ext = ltrim($ext, '.');
		$this->renderClasses[$ext] = $name;
		return $this;
	}
	
	public function getErrorMsg() {	
		return $this->errorMsg;
	}

	public function response($name='') {	
		$this->responseSet = true;
		$this->responseName = $name;
		return $this;
	}

	public function load($scope=null) {
		if (is_array($scope)) {
			$scope = $scope[0];
		}
		if (! isset($this->paths[$scope])) {
			$scope = 'module';	 // the default setting e.g., "/app/module/models"
		}
		$this->scope = $scope;
		$this->scopePath = $this->paths[$scope];
		$this->responseSet = false;		// reset response mode to off for each call
		return $this;
	}

	public function __call($type, $params) {
		$obj = null;
		// is this a defined type of subdirectory
		if (isset($this->dirs[$type])) {
			// get class name parameter or use action name or use method name in controller scope for $type/$controller/$action.php
			$class = isset($params[0]) && $params[0] ? $params[0] : ($this->scope == 'controller' && $this->method ? $this->method : $this->action);
			if (isset($this->suffix[$type])) {
				$length = strlen($this->suffix[$type]);
				// if a suffix is defined and the end of the action name does not contain it -- append it
				if ($length && (substr($class, -$length) != $this->suffix[$type])) {
					$class .= $this->suffix[$type];
				}
			}
			
			// insert type path into scope path
			if ($this->scopePath) { 
				$path = str_replace('%s', $this->dirs[$type], $this->scopePath);
			} else {
				$path = $this->dirs[$type];		// just in case no scopePath
			}
			
			// helpers take a parent instance as the parameter
			if ($type == 'helper') {
				$params[1] = $this->parent;
			}
			
			// templates are a template filename, not a class name -- need to load/create template class
			if ($type == 'template') {
				// lookup the renderer by extension, if given
				$path_parts = pathinfo($class);
				// if dir in name the add to path
				if ($path_parts['dirname'] != '.') {
					$path .= trim($path_parts['dirname'], '/') . '/';
				}
				// if extension found and it is in array, use it
				$ext = isset($path_parts['extension']) && isset($this->renderClasses[$path_parts['extension']]) ? $path_parts['extension'] : $this->renderExtension; 
		        // fix by thinsoldier for NT servers
				$class = $path_parts['basename'];

				include_once str_replace('_', '/', $this->renderClasses[$ext]) . '.php';
				$obj = new $this->renderClasses[$ext]("$path$class.$ext");
				// if 2nd param is array then use it to set template values
				if (isset($params[1]) && is_array($params[1])) {
					foreach ($params[1] as $key => $val) {
						$obj->set($key, $val);
					}
				}
			} elseif ($this->locator) {
				if ($this->locator->loadClass($class, $path)) { // load class if necessary
					$obj = new $class(isset($params[1]) ? $params[1] : $this->locator);
				} else {
					$this->errorMsg .=  "Error: locator->loadClass('$class', '$path'). ";
				}
			}
			// initialize object
			if ($obj) {
				// template and view need passed values set
				switch ($type) {
				case 'template':
				case 'view':
					if (isset($params[1]) && is_array($params[1])) {
						// if 2nd param is array then use it to set template values
						foreach ($params[1] as $key => $val) {
							$obj->set($key, $val);
						}
					}
					break;
				case 'helper':
					$this->parent->setHelper($params[0], $obj);
					break;
				}
				 // this is the section for when response() has been called
				 if ($this->responseSet) {
					 if ($this->locator) {
					 	$response = $this->locator->get('Response');
						if ($response && $obj) {					
							if ($this->responseName) {
								$response->set($this->responseName, $obj);		// if name then set data in response
							
							} elseif (in_array($type, $this->rendererTypes)) {	// if renderer set as renderer
								$response->setRenderer($obj);
							} else {
								$response->set($class, $obj);					// otherwise set by class name
							}
						} else {
							echo $obj->render();	// do we really want this option? or should the action do this?
						}
						return $this;				// if response set then allow chained
					} else {
						$this->errmsg .= "No registry passed to __construct(). ";
					}
				}
			} else {
				$this->errorMsg .= "Could not load() {$this->dirs[$type]}{$this->scopePath}.php. ";
			}
			//reset scope and response
			$this->scope = null;
			$this->scopePath = null;
			$this->responseSet = false;
			return $obj;
		}
	}

}
