<?php

class A_Controller_Action {
	protected $_path = '';
	protected $_dir = array('model'=>'models', 'view'=>'views', );
	protected $_action = null;
	protected $_locator;
	 
	public function __construct($locator){
	    $this->_locator = $locator;
	}
	 
	// 						$global_path . $module_dir . $mapper->class_dir
	public function initialize($global_path, $module_dir, $local_dir, $class){
		$this->_path['local'] = $global_path . $module_dir . $local_dir;
		$this->_path['module'] = $global_path . $module_dir;
		$this->_path['global'] = $global_path;
		$this->_action = $class;
	}
		
	protected function addPath($name, $path, $relative_name=''){
	    if ($relative_name) {
	    	$this->_path[$name] = $this->_path[$relative_name] . $path;
	    } else {
	    	$this->_path[$name] = $path;
	    }
	}
	
	protected function _load($name, $path='module') {
	    $this->_locator->loadClass($name, $this->dir[$path]);
	}
	
	protected function getPhpRenderer($path='module') {
	    if (! class_exist('A_Template_Include')) include 'A/Template/Include.php';
	    
	    $filename = $this->_path[$path] . $this->_dir['view'] . $this->_action . '.php';
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
