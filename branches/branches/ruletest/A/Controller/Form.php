<?php
/**
 * Form.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_Form
 * 
 * Controller class for callback style form support
 */
class A_Controller_Form extends A_Controller_App {
	protected $state_handlers;
	protected $state_param = 'A_Controller_App_State';
	protected $state_name_init = 'init';
	protected $submit_param_name = 'submit';
	
	public function __construct($locator=null, $state_handlers=array()) {
		parent::__construct($locator, $this->state_name_init);
		$this->state_handlers = $state_handlers;
	}
	
	public function setStateHandlers($state_handlers) {
		$this->state_handlers = $state_handlers;
		return $this;
	}
	
	public function setSubmitFieldName($name) {
		if ($name) {
			$this->submit_param_name = $name;
		}
		return $this;
	}
	
	public function getSaveValues() {
		$data = array();
		foreach (array_keys($this->params) as $field) {
			if ($this->params[$field]->save) {
				$data[$field] = $this->params[$field]->value;
			}
		}
		return $data;
	}

	public function run($locator) {
	
		$request = $locator->get('Request');
		if (! $this->state_name) {
			$this->state_name = $this->state_name_init;
		}
// register states managed by this controller
		if (is_array($this->state_handlers)) {
			foreach (array_keys($this->state_handlers) as $state) {
				$this->addState(new A_Controller_App_State($state, $this->state_handlers[$state]));
			}
		}
	
// register register transitions with from state, to states and rule
		$this->addTransition(new A_Controller_App_Transition('init', 'submit', new A_Rule_Notnull($this->submit_param_name, 'submit')));
		$this->addTransition(new A_Controller_App_Transition('submit', 'done', $this));
	
		$this->processRequest($request);
	
		return parent::run($locator);
	}

	public function getSourceNames() {
		$data = array();
		foreach (array_keys($this->params) as $field) {
			if ($this->params[$field]->source_name) {
				$data[$field] = $this->params[$field]->source_name;
			} else {
				$data[$field] = $this->params[$field]->name;
			}
		}
		return $data;
	}

}
