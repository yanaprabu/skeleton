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
	}
		
	protected function setPath($name, $path, $relative_name=''){
		$path = $path ? (rtrim($path, '/') . '/') : '';		// add trailing dir separator
		if ($relative_name) {
			$this->paths[$name] = $this->paths[$relative_name] . $path;
		} else {
			$this->paths[$name] = $path;
		}
	}
	
	protected function setDir($name, $dir){
		$this->dirs[$name] = $dir ? (rtrim($dir, '/') . '/') : '';
	}
	
	protected function setRenderClass($name){
		$this->renderClass = $name;
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
			} elseif ($this->locator) {
				if ($this->locator->loadClass($class, $this->scopePath . $this->dirs[$type])) { // load class if necessary
					$obj = new $class($this->locator);
				}
			}

			if ($obj && $this->responseSet) { // this is the section for when setResponse() is called
				$response = $this->locator->get('Response');
				if ($response && $obj) {
					if ($this->responseName) {
						$response->set($this->responseName, $obj);
					} else {
						$response->setContent($obj->render());
					}
				} else {
					echo $obj->render();	// do we really want this option? or should the action do this?
				}
			}

			if (! $obj) {
				$this->errorMsg .= "Could no load() {$this->dirs[$type]}{$this->scopePath}.php";
			}
			//reset scope and response
			$this->scopePath = null;
			$this->responseSet = false;
			return $obj;
		}
	}

}
