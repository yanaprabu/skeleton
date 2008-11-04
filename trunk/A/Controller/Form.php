<?php
include_once 'A/Controller/App.php';
include_once 'A/Rule/Notnull.php';
/**
 * Controller class for callback style form support
 *
 * @package A_Controller
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
	
	public function setSubmitParameterName($name) {
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


class A_Controller_FormParameter extends A_Controller_InputParameter {
public $type = '';
public $addtype = '';
public $default = '';
public $source_name = '';
public $save = true;
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function setType($type, $addtype='') {
		$this->type = $type;
		$this->addtype = $addtype;
		return $this;
	}
	
	public function setDefault($value) {
		$this->default = $value;
		return $this;
	}
	
	public function setSourceName($value) {
		$this->source_name = $value;
		return $this;
	}
	
	public function setSave($value=true) {
		$this->save = $value;
		return $this;
	}

	public function render() {
		if ($this->addtype && ($this->value == '')) {
			$savetype = $this->type;
			$this->type = $this->addtype;
			$result = parent::render();
			$this->type = $savetype;
			return $result;
		} else {
			return parent::render();
		}
	}

}
