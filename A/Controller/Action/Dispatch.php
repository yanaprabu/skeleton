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
		if(!$this->response) {
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
		return $this->getResponse()->setRedirect($url);
	}
	
	/**
	 * Queries request for parameters
	 *
	 * @param string $param
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParam($param,$default = null) {
		return $this->request->get($param,$default);
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
			$this->preDispatch();
			$this->$action($locator);
			$this->postDispatch();
		} else {
			// set error here
		}
	}
	
	/**
	 * load view object
	 */
	public function view($name='', $scope='') {
		if (!$this->view) {
			$this->view = $this->load($scope)->view($name);
		}
		return $this->view;
	}
	
	/**
	 * render view
	 */
	public function render() {
		$this->response->setContent($this->view->render());
		return $this->response->render();
	}
	
	/**
	 * Pre-dispatch hook
	 */
	public function preDispatch() {}
	
	/**
	 * Post-dispatch hook
	 */
	public function postDispatch() {}
	
}