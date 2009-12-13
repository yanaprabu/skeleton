<?php
#include_once 'A/Controller/Action.php';

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
	 * Dispatch request
	 *  - Register request object
	 *  - Activate pre- and post-dispatch hooks
	 * @param string $action
	 * @param A_Locator $locator
	 */
	public function _dispatch($locator, $action) {
		if (method_exists($this, $action)) {
			$this->_preDispatch();
			$this->$action($locator);
			$this->_postDispatch();
		} else {
			// set error here
		}
	}
	
	/**
	 * Pre-dispatch hook
	 */
	public function _preDispatch() {}
	
	/**
	 * Post-dispatch hook
	 */
	public function _postDispatch() {}
	
}