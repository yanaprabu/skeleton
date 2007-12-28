<?php

class A_Controller_Action {
	protected $paths = array();
	protected $dirs = array('model'=>'models', 'view'=>'views', );
	protected $action = null;
	protected $locator;
	protected $loader = null;
	
	public function __construct($locator){
	    $this->locator = $locator;
	}
	 
	// 						$globalpaths . $moduledirs . $mapper->classdirs
	public function initialize($globalpaths, $moduledirs, $localdirs, $class){
		$this->paths['local'] = $globalpaths . $moduledirs . $localdirs;
		$this->paths['module'] = $globalpaths . $moduledirs;
		$this->paths['global'] = $globalpaths;
		$this->action = $class;
	}
		
	protected function addPath($name, $path, $relative_name=''){
	    if ($relative_name) {
	    	$this->paths[$name] = $this->paths[$relative_name] . $path;
	    } else {
	    	$this->paths[$name] = $path;
	    }
	}
	
	protected function _load($name, $path='module') {
	    $this->locator->loadClass($name, $this->dir[$path]);
	}
	
	protected function load($module=null) {
		if (! $this->loader) {
		    include_once 'A/Controller/Action/Loader.php';
			$this->loader = new A_Controller_Action_Loader($this->locator, $this->paths, $this->dirs, $this->action);
		}
		return $this->loader->setScope($module);
	}

	protected function getPhpRenderer($path='module') {
	    include_once 'A/Template/Include.php';
	    $filename = $this->paths[$path] . $this->dirs['view'] . $this->action . '.php';
	    $renderer = new A_Template_Include($filename);
	    return $renderer;
	}
	
	protected function _forward($dir, $class, $method, $args=null){
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
