<?php

class A_Controller_Action {
	protected $paths = array();
	protected $dirs = array('model'=>'models/', 'view'=>'views/', 'template'=>'templates/', );
	protected $action = null;
	protected $locator;
	protected $loader = null;
	
	public function __construct($locator){
	    $this->locator = $locator;
	}
	 
	// 						$globalpath . $moduledir . $mapper->classdir
	public function initialize($globalpath, $moduledir, $localdir, $class){
		$this->path['local'] = $globalpath . $moduledir . $localdir;
		$this->path['module'] = $globalpath . $moduledir;
		$this->path['global'] = $globalpath;
		$this->action = $class;
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
	
	protected function load($module=null) {
		if (! $this->loader) {
		    include_once 'A/Controller/Action/Loader.php';
			$this->loader = new A_Controller_Action_Loader($this->locator, $this->paths, $this->dirs, $this->action);
		}
		return $this->loader->setScope($module);
	}

	protected function forward($dir, $class, $method, $args=null){
		$forward = new A_DL($dir, $class, $method, $args=null);
		return $forward;
	}
 
}


/*
 * pass object to A_Controller_Front addPreFilter()
 */
class A_Controller_Action_Injector {
	protected $mapper;
	/*
	 * $mapper parameters is instance of A_Controller_Mapper
	 */
	public function __construct($mapper) {
		$this->mapper = $mapper;
	}
	
	public function run($controller) {
		if ($controller instanceof A_Controller_Action) {
			$controller->initialize($this->mapper->getBasePath(), $this->mapper->getDir(), $this->mapper->getClassDir(), $this->mapper->getClass());
		}
	}
	
}
