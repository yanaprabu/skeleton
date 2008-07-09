<?php

/**
 * Action Controller Dispatch
 * 
 * This action controller implementation controls the actual action dispatch process and allows for pre- and post- dispatch hooks to be used
 * 
 * @category Skeleton
 * @package Controller
 * @subpackage Action
 */
class A_Controller_Action_Dispatch {
	
	/**
	 * The dispatched request
	 * @var A_Request
	 */
	protected $_request = null;
	
	/**
	 * The result response
	 * @var A_Reponse
	 */
	protected $_repsponse = null;
	
	/**
	 * Registered plugins
	 * @var array
	 */
	protected $_plugins = array();
	
	/**
	 * Request getter
	 * @return A_Request
	 */
	public function getRequest() {
		return $this -> _request;
	}
	
	/**
	 * Reponse getter
	 *  - Create a new response object if it has not been set
	 * @return A_Reponse
	 */
	public function getResponse() {
		if(!$this -> _response instanceof A_Response) {
			$this -> _response = new A_Http_Reponse();
		}
		
		return $this -> _response;
	}

	/**
	 * Send redirect headers
	 *  - Asks response object to send redirect headers
	 *
	 * @param string $url
	 */
	public function redirect($url) {
		return $this -> getResponse() -> redirect($url);
	}
	
	/**
	 * Queries request for parameters
	 *
	 * @param string $param
	 * @param mixed $default
	 * @return mixed
	 */
    public function getParam($param,$default = null) {
    	return $this -> getRequest() -> getParam($param,$default);
    }
    
    /**
     * Dispatch request
     *  - Register request object
     *  - Activate pre- and post-dispatch hooks
     * @param A_Request $request
     */
    public function dispatch(A_Request $request) {
    	$this -> _request = $request;
    	$action = $request -> getActionName();
    	   	
    	$this -> preDispatch();
    	$this -> $action;
    	$this -> postDispatch();
    }
    
    /**
     * Pre-dispatch hook
     */
    public function preDispatch() {}
    
    /**
     * Post-dispatch hook
     */
    public function postDispatch() {}
    
    /**
     * Register plug-in
     *  - Returns plug-in if only key is specified
     * @param string $key
     * @param A_Controller_Plugin $plugin
     * @return mixed
     */
    public function plug($key,$plugin = null) {
    	if(!is_string($key) || empty($key)) {
    		throw new A_Controller_Exception('Plugin key must be a non-empty string');
    	}
    	
    	if($plugin instanceof A_Controller_Plugin) {
    		return $this -> _plugins[$key] = $plugin;
    	} 
    	
    	return (isset($this -> _plugins[$key])) ? $this -> _plugins[$key] : null;
    }
}