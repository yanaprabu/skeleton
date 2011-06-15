<?php
/**
 * App.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_App
 * 
 * Application controller class for state/transition based action selection
 */
class A_Controller_App extends A_Controller_Input
{

	protected $state_name = '';
	protected $state_name_init = '';
	protected $no_init_errors = true;
	protected $states = array();
	protected $transition = array();
	
	public function __construct($locator=null, $state_name='')
	{
	    parent::__construct($locator);
		$this->setInitState($state_name);
	}
	
	public function setInitState($state_name)
	{
		$this->state_name = $state_name;
		$this->state_name_init = $state_name;
		return $this;
	}
	
	public function addState($state)
	{
		if (($state instanceof A_Controller_App_State) && $state->name) {
			$this->states[$state->name] = $state;
		}
		return $this;
	}
	
	public function addTransition($transition)
	{
		if ($transition->fromState && $transition->toState) {
			$this->transitions[$transition->fromState][] = $transition;
		}
		return $this;
	}
	
	public function findState($request)
	{
		// if no initial state then set to first state
		if (!$this->state_name && $this->states) {
			reset($this->states);
			$state = current($this->states);
			$this->state_name = $state->name;
		}
		do {
			$run = false;
			$n = count($this->transitions[$this->state_name]);
			for ($i = 0; $i < $n; $i++) {
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
	
    public function run($locator)
    {
		$state = $this->findState($locator->get('Request'));
		
		// clear any error messages on init
		if ($this->no_init_errors && ($this->state_name_init == $state->name)) {
			foreach (array_keys($this->params) as $field) {
				$this->params[$field]->setError(null);
			}
		}
		
		// run code for this state
		if (isset($state->handler)) {
			if (is_object($state->handler)) {
				$state->handler->run($locator);				// is DL
			} else {
				call_user_func($state->handler, $locator);	// is string or array 
			}
		}
    }

}
