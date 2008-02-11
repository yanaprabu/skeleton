<?php
include_once 'A/Controller/Input.php';

class A_Controller_App extends A_Controller_Input {
	protected $state_name = '';
	protected $state_name_init = '';
	protected $no_init_errors = true;
	protected $states = array();
	protected $transition = array();

	public function __construct($locator=null, $state_name='') {
	    parent::__construct($locator);
		$this->setInitState($state_name);
	}

	public function setInitState($state_name) {
		$this->state_name = $state_name;
		$this->state_name_init = $state_name;
		return $this;
	}

	public function addState($state) {
		if (($state instanceof A_Controller_App_State) && $state->name) {
			$this->states[$state->name] = $state;
		}
		return $this;
	}
	
	public function addTransition($transition) {
		if ($transition->fromState && $transition->toState) {
			$this->transitions[$transition->fromState][] = $transition;
		}
	}
	
	public function findState($request) {
		do {
			$run = false;
			$n = count($this->transitions[$this->state_name]);
			for ($i=0; $i<$n; ++$i) {
				if ($this->transitions[$this->state_name][$i]->rule->isValid($request) && $this->transitions[$this->state_name][$i]->condition) {
					$this->state_name = $this->transitions[$this->state_name][$i]->getToState();
					if (isset($this->transitions[$this->state_name])) {
						$run = true;
					}
					break;
				}
			}
		} while ($run);

		if (isset($this->states[$this->state_name])) {
			return $this->states[$this->state_name];
		}
		$handler = null;
		return $handler;
	}

    public function run($locator) {
		$state = $this->findState($locator->get('Request'));

// clear any error messages on init
		if ($this->no_init_errors && ($this->state_name_init == $state->name)) {
			foreach (array_keys($this->params) as $field) {
				$this->params[$field]->error_msg = array();
			}
		}

		parent::addHandler($state->handler);
		parent::run($locator);
    }

}

class A_Controller_App_State {
	public $name;
	public $handler;

	public function __construct($name, $handler) {
		$this->name = $name;
		$this->handler = $handler;
	}
	
}

class A_Controller_App_Transition {
	public $fromState;
	public $toState;
	public $rule;
	public $condition;

	public function __construct($fromState, $toState, $rule, $condition=true) {
		$this->fromState = $fromState;
		$this->toState = $toState;
		$this->rule = $rule;
		$this->condition = $condition;
	}
	
	public function getToState() {
		return $this->toState;
	}
	
}
