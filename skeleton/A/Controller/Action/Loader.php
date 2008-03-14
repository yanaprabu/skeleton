<?php

class A_Controller_Action_Loader {
	protected $locator;
	protected $paths = array('global'=>'', 'module'=>'', 'controller'=>'', 'action'=>'');
	protected $dirs = array('helper'=>'helpers/', 'model'=>'models/', 'view'=>'views/', 'template'=>'templates/', );
	protected $action = null;
	protected $method = null;
	protected $suffix = array('model'=>'Model', 'view'=>'View', 'helper'=>'Helper');
	protected $rendererTypes = array('view', 'template');
	protected $scope;
	protected $scopePath;
	protected $responseName = '';
	protected $renderClass = 'A_Template_Include';
	protected $renderExtension = '.php';
	protected $responseSet = false;
	protected $errorMsg = '';
	
	public function __construct($locator){
		$this->locator = $locator;
		if ($locator) {
			$mapper = $locator->get('Mapper');
			if ($mapper) {
				$this->setMapper($mapper);
			}
		}
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
			$this->paths['global'] = $mapper->getBasePath();
			$this->paths['module'] = $this->paths['global'] . $mapper->getDir();
			$this->paths['controller'] = $this->paths['module'] . $type . $this->action . '/';// . $mapper->getClassDir();
			$this->paths['action'] = $this->paths['controller'] . ($this->method ? "$this->method/" : '');
			$this->paths['global'] .= $type;
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
	
	public function setRenderClass($name, $ext='.php'){
		$this->renderClass = $name;
		$this->renderExtension = $ext;
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
			$path = str_replace('%s', $this->dirs[$type], $this->scopePath);
			
			// templates are a template filename, not a class name -- need to load/create template class
			if ($type == 'template') {
				include_once str_replace('_', '/', $this->renderClass) . '.php';
				$obj = new $this->renderClass("$path$class" . $this->renderExtension);
				// if 2nd param is array then use it to set template values
				if (isset($params[1]) && is_array($params[1])) {
					foreach ($params[1] as $key => $val) {
						$obj->set($key, $val);
					}
				}
			} elseif ($this->locator) {
				if ($this->locator->loadClass($class, $path)) { // load class if necessary
					$obj = new $class($this->locator);
				} else {
					$this->errorMsg .=  "Error: locator->loadClass('$class', '$path'). ";
				}
			}

			if ($obj && $this->responseSet) { // this is the section for when response() has been called
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
			}

			if (! $obj) {
				$this->errorMsg .= "Could not load() {$this->dirs[$type]}{$this->scopePath}.php. ";
			}
			//reset scope and response
			$this->scopePath = null;
			$this->responseSet = false;
			return $obj;
		}
	}

}
