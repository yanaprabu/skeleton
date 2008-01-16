<?php

class A_Controller_Action_Loader {
	protected $locator;
	protected $paths;
	protected $dirs;
	protected $action;
	protected $suffix = array('model'=>'Model', 'view'=>'View');
	protected $scopePath;
	protected $responseName = '';
	protected $responseSet = false;
	
	public function __construct($locator, $paths, $dirs, $action) {
		$this->locator = $locator;
		$this->paths = $paths;
		$this->dirs = $dirs;
		$this->action = $action;
	}
	 
	public function setScope($module=null) {
		if (! isset($this->paths[$module])) {
			$module = 'module';     // the default setting e.g., "/app/module/models"
		}
		$this->scopePath = $this->paths[$module];
		return $this;
	}

	public function response($name='') {	
		$this->responseSet = true;
		$this->responseName = $name;
		return $this;
	}

	public function __call($type, $params) {
		// is this a defined type of subdirectory
		if (isset($this->dirs[$type])) {
			// get class name parameter or use action name
			$class = isset($params[0]) ? $params[0] : $this->action;
			if (isset($this->suffix[$type])) {
				$length = strlen($this->suffix[$type]);
				// if a suffix is defined and the end of the action name does not contain it -- append it
				if ($length && (substr($class, -$length) != $this->suffix[$type])) {
					$class .= $this->suffix[$type];
				}
			}
			
			if ($this->responseSet) { // this is the section for when setResponse() is called
				// templates are a template filename, not a class name -- need to load/create template class
				if ($type == 'template') {
				    include_once 'A/Template.php';
				    $obj = new A_Template_Include($this->scopePath . $this->dirs['template'] . $class . '.php');
				} else {
					$obj = $this->locator->get($class, $class, $this->scopePath . $this->dirs[$type]); // load class if necessary
				}

				$response = $this->locator->get('Response');
				if ($response) {
					if ($this->responseName) {
						$response->set($this->responseName, $obj);
					} else {
						$response->setContent($obj->render());
					}
				} else {
					echo $obj->render();	// do we really want this option? or should the action do this?
				}
			} else {
				// load class if necessary
				$obj = $this->locator->get($class, $class, $this->scopePath . $this->dirs[$type]);
			}

			//reset scope and response
			$this->scopePath = null;
			$this->responseSet = false;
			return $obj;
		}
	}

}
