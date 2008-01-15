<?php

class A_Controller_Action_Loader {
	protected $locator;
	protected $paths;
	protected $dirs;
	protected $action;
	protected $suffix;
	protected $scopePath;
	protected $responseName = '';
	
	public function __construct($locator, $paths, $dirs, $action) {
		$this->locator = $locator;
		$this->paths = $paths;
		$this->dirs = $dirs;
		$this->action = $action;
		$this->suffix = array('model'=>'Model', 'view'=>'View', );
	}
	 
	public function setScope($module=null) {
		if (! isset($this->paths[$module])) {
			$module = 'module';     // the default setting e.g., "/app/module/models"
		}
		$this->scopePath = $this->paths[$module];
		return $this;
	}

	public function setResponse($name='') {
		$this->responseName = $name;
		return $this;
	}

	public function __call($type, $params) {
		// is this a defined type of subdirectory
		if (isset($this->dirs[$type])) {
			// get class name parameter or use action name
			if (isset($params[0])) {
				$class = $params[0];
			} else {
				$class = $this->action;
			}
			if (isset($this->suffix[$type])) {
				$length = strlen($this->suffix[$type]);
				// if a suffix is defined and the end of the action name does not contain it -- append it
				if ($length && (substr($class, -$length) != $this->suffix[$type])) {
					$class .= $this->suffix[$type];
				}
			}
			
			if (strlen($this->responseName)) {
				// this is the section for when setResponse() is called
				
				// templates are a template filename, not a class name -- need to load/create template class
				if ($type == 'template') {
				    include_once 'A/Template.php';
				    $obj = new A_Template_Include($this->scopePath . $this->dirs['template'] . $class . '.php');
				} else {
					// load class if necessary
					$obj = $this->locator->get($class, $class, $this->scopePath . $this->dirs[$type]);
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
			
			return $obj;
		}
	}

}
