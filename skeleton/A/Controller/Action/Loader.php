<?php

class A_Controller_Action_Loader {
	protected $locator;
	protected $paths = array('global'=>'', 'module'=>'', 'local'=>'');
	protected $dirs = array('helper'=>'helpers/', 'model'=>'models/', 'view'=>'views/', 'template'=>'templates/', );
	protected $action = null;
	protected $suffix = array('model'=>'Model', 'view'=>'View');
	protected $scopePath;
	protected $responseName = '';
	protected $renderClass = 'A_Template_Include';
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
	 
	public function setMapper($mapper){
		if ($mapper) {
			$this->paths['global'] = $mapper->getBasePath();
			$this->paths['module'] = $this->paths['global'] . $mapper->getDir();
			$this->paths['local'] = $this->paths['module'] . $mapper->getClassDir();
			$this->action = $mapper->getClass();
		}
		return $this;
	}
		
	protected function setPath($name, $path, $relative_name=''){
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
	
	protected function setRenderClass($name){
		$this->renderClass = $name;
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

	public function load($module=null) {
		if (! isset($this->paths[$module])) {
			$module = 'module';	 // the default setting e.g., "/app/module/models"
		}
		$this->scopePath = $this->paths[$module];
		return $this;
	}

	public function __call($type, $params) {
		$obj = null;
		// is this a defined type of subdirectory
		if (isset($this->dirs[$type])) {
			// get class name parameter or use action name
			$class = isset($params[0]) && $params[0] ? $params[0] : $this->action;
			if (isset($this->suffix[$type])) {
				$length = strlen($this->suffix[$type]);
				// if a suffix is defined and the end of the action name does not contain it -- append it
				if ($length && (substr($class, -$length) != $this->suffix[$type])) {
					$class .= $this->suffix[$type];
				}
			}
			
			// templates are a template filename, not a class name -- need to load/create template class
			if ($type == 'template') {
				include_once str_replace('_', '/', $this->renderClass) . '.php';
				$obj = new $this->renderClass($this->scopePath . $this->dirs['template'] . $class . '.php');
				// if 2nd param is array then use it to set template values
				if (isset($params[1]) && is_array($params[1])) {
					foreach ($params[1] as $key => $val) {
						$obj->set($key, $val);
					}
				}
			} elseif ($this->locator) {
				if ($this->locator->loadClass($class, $this->scopePath . $this->dirs[$type])) { // load class if necessary
					$obj = new $class($this->locator);
				} else {
					$this->errorMsg .=  "Error: locator->loadClass('$class', '{$this->scopePath}', '{$this->dirs[$type]}'). ";
				}
			}

			if ($obj && $this->responseSet) { // this is the section for when response() has been called
				$response = $this->locator->get('Response');
				if ($response && $obj) {
					// if name then set data in response, otherwise use as renderer
					if ($this->responseName) {
						$response->set($this->responseName, $obj);
					} else {
						$response->setRenderer($obj);
					}
				} else {
					echo $obj->render();	// do we really want this option? or should the action do this?
				}
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
