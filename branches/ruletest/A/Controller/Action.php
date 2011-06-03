<?php
/**
 * Action.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_Action
 * 
 * Basic MVC controller functionality.  Meant to be extended by controller classes to provide them with tools with which to interface with the framework.
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
	 * array of helpers
	 * @var object
	 */
	protected $errorMsg = array();
	
	/**
	 * constructor called by the Front Controller
	 * note: called A_Controller_Action and not __construct to avoid Strict warning. 
	 *       If child implements __construct() the this method ignored
	 */
	public function __construct($locator=null){
	    if ($locator) {
			$this->locator = $locator;
			$this->request = $locator->get('Request');
			$this->response = $locator->get('Response');
	    }
	}
	 
	/**
	 * Get Response object or gets a parameter
	 *
	 * @param string $name
	 * @param mixed $filter
	 * @param mixed $default
	 * @return A_Http_Request
	 */
	public function _request($name=null, $filter=null, $default=null) {
		if ($name) {
			return $this->request->get($name, $filter, $default);
		}
		return $this->request;
	}
	
	/**
	 * Get Response object or set a value
	 * Creates a new response object if it has not been set
	 * @param string $name
	 * @param mixed $value
	 * @return A_Http_Reponse
	 */
	public function _response($name=null, $value=null) {
		if(!$this->response) {
			$this->response = new A_Http_Response();
		}
		if($name) {
			$this->response->set($name, $value);
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
			$this->load = new A_Controller_Helper_Load($this->locator, $this, $scope);
		}
		return $this->load;
	}
 
	public function _flash($name=null, $value=null) {
		if (! isset($this->flash)) {
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
	
	/**
	 * get error messages
	 */
	public function getErrorMsg($separator="\n") {
		$errormsg = $this->errorMsg;
		if ($this->load) {
			$errormsg = array_merge($errormsg, $this->load->getErrorMsg(''));	// get load errors as an array
		}
		if ($separator) {
			$errormsg = implode($separator, $errormsg);
		}
		return $errormsg;
	}
	
}
