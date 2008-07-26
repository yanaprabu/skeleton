<?php
include_once 'A/Controller/Action.php';

/**
 * Action Controller Dispatch
 * 
 * This action controller implementation controls the actual action dispatch process and allows for pre- and post- dispatch hooks to be used
 * 
 * @category Skeleton
 * @package A_Controller
 * @subpackage Action
 */
class A_Controller_Action_Dispatch extends A_Controller_Action {
	
	/**
	 * The dispatched request
	 * @var A_Request
	 */
	protected $request = null;
	
	/**
	 * The result response
	 * @var A_Reponse
	 */
	protected $response = null;
	
	/**
	 * Registered plugins
	 * @var array
	 */
	protected $plugins = array();
	
	/**
	 * Request getter
	 * @return A_Request
	 */
	public function getRequest() {
		return $this->request;
	}
	
	/**
	 * Reponse getter
	 *  - Create a new response object if it has not been set
	 * @return A_Reponse
	 */
	public function getResponse() {
		if(!$this->response instanceof A_Response) {
			$this->response = new A_Http_Reponse();
		}
		
		return $this->response;
	}

	/**
	 * Send redirect headers
	 *  - Asks response object to send redirect headers
	 *
	 * @param string $url
	 */
	public function redirect($url) {
		return $this->getResponse()->redirect($url);
	}
	
	/**
	 * Queries request for parameters
	 *
	 * @param string $param
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParam($param,$default = null) {
		return $this->getRequest()->getParam($param,$default);
	}
	
	/**
	 * Dispatch request
	 *  - Register request object
	 *  - Activate pre- and post-dispatch hooks
	 * @param string $action
	 * @param A_Locator $locator
	 */
	public function dispatch(A_Locator $locator, $action) {
		if (method_exists($this, $action)) {
			$this->request = $locator->get('Request');
				   	
			$this->preDispatch();
			$this->$action($locator);
			$this->postDispatch();
		} else {
			// set error here
		}
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
	public function plug($key, $plugin = null) {
		if(!is_string($key) || empty($key)) {
//			throw new A_Controller_Exception('Plugin key must be a non-empty string');
		}
		
		if($plugin instanceof A_Controller_Plugin) {
			return $this->plugins[$key] = $plugin;
		} 
		
		return (isset($this->plugins[$key])) ? $this->plugins[$key] : null;
	}
}