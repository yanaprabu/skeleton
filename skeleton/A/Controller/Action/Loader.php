<?php

class A_Controller_Action_Loader {
	protected $locator;
	protected $paths;
	protected $dirs;
	protected $action;
	protected $suffix;
	protected $scope_path;

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
		$this->scope_path = $this->paths[$module];
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
			// load class if necessary and return instance
			return $this->locator->get($class, $class, $this->scope_path . $this->dirs[$type]);
		}
	}

}
