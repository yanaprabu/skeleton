<?php
/**
 * Basic MVC controller functionality
 * 
 * @package A_Controller 
 */

class A_Controller_Action {
	const APP = 'app';
	const MODULE = 'module';
	const CONTROLLER = 'controller';
	const ACTION = 'action';

	/**
	 * registry
	 * @var A_Request
	 */
	protected $locator;

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
	 * The result response
	 * @var A_Controller_Helper_Load
	 */
	protected $load = null;

	/**
	 * View object for simple single view
	 * @var A_Http_View
	 */
	protected $view = null;

	/**
	 * array of helpers
	 * @var object
	 */
	protected $helpers = array();
	
	/**
	 * constructor called by the Front Controller
	 */
	public function __construct($locator=null){
	    if ($locator) {
			$this->locator = $locator;
			$this->request = $locator->get('Request');
			$this->response = $locator->get('Response');
	    }
	}
	 
	/**
	 * Queries request for parameters
	 *
	 * @param string $param
	 * @param mixed $default
	 * @return mixed
	 */
	public function _request($param=null, $filter=null, $default=null) {
		if ($param) {
			return $this->request->get($param, $filter, $default);
		}
		return $this->request;
	}
	
	/**
	 * Reponse getter
	 *  - Create a new response object if it has not been set
	 * @return A_Reponse
	 */
	public function _response() {
		if(!$this->response) {
			include_once 'A/Http/Response.php';
			$this->response = new A_Http_Response();
		}
		
		return $this->response;
	}

	/**
	 * Send redirect headers
	 *  - Asks response object to send redirect headers
	 *
	 * @param string $url
	 */
	public function _redirect($url) {
		if (isset($this->response)) {
			$this->response->setRedirect($url);
		} else {
			header("Location: $url");
		}
		return $this;
	}
	
	/**
	 * return the result of this function to the Front Controller to forward to another controllere
	 *
	 * @param string $dir
	 * @param string $class
	 * @param string $method
	 * @param string $args
	 * @return array
	 */
	public function _forward($dir, $class, $method='', $args=null){
		return array($dir, $class, $method, $args);
	}
 
	/**
	 * create aa A_Controller_Helper_Load obejct and return it for loading functionality
	 * @param string $scope
	 * @return array
	 */
	public function _load($scope=null) {
		if (isset($this->load)) {
			$this->load->load($scope);
		} else {
			include_once "A/Controller/Helper/Load.php";
			$this->load = new A_Controller_Helper_Load($this->locator, $this, $scope);
		}
		return $this->load;
	}
 
	public function _flash($name=null, $value=null) {
		if (! isset($this->flash)) {
			include_once "A/Controller/Helper/Flash.php";
			$this->flash = new A_Controller_Helper_Flash($this->locator);
		}
		if ($name) {
			if ($value) {
				$this->flash->set($name, $value);
			} else {
				return $this->flash->get($name);
			}
		}
		return $this->flash;
	}
 
	public function _helper($name, $helper='') {
		if ($helper) {
			$this->helpers[$name] = $helper;
		}
		if (isset($this->helpers[$name])) {
			return $this->helpers[$name];
		}
	}
 
	/**
	 * load view object
	 */
	public function _view($name='', $scope='') {
		if (!$this->view) {
			$this->view = $this->load($scope)->view($name);
		}
		return $this->view;
	}
	
}
