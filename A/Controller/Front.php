<?php
include_once 'A/Locator.php';
include_once 'A/DL.php';
/**
 * A_Controller_Front
 * 
 * This is an implementation of the Front Controller pattern. It is one of the buidling blocks of the MVC structure in Skeleton
 *
 * @package A_Controller
 */
class A_Controller_Front {
	
	/**
	 * Error constans
	 */
	const NO_ERROR = 0;
	const CLASS_NOT_LOADED = 1;
	const METHOD_NOT_FOUND = 2;
	
	/**
	 * The front controller mapper object
	 * @var A_Mapper
	 */
	protected $_mapper = null;
	
	/**
	 * Not sure what this is exactly, I assume A_DL?
	 * @var A_DL
	 */
	protected $_error_action;
	
	/**
	 * Pre-dispatch filters
	 * @var array
	 */
	protected $_prefilters = null;
	
	/**
	 * The front controller locator object
	 * @var A_Locator
	 */
	protected $_locator = null;
	
	/**
	 * Stack of dispatched routes
	 * @var array
	 */
	protected $_routes = array();	
	
	/**
	 * Error indicator
	 * @var int
	 */
	protected $_error = self::NO_ERROR;
	
	/**
	 * Class constructor
	 *
	 * @param A_Controller_Mapper $mapper
	 * @param A_DL $error_action Still not sure how this works. Also, is it optional?
	 * @param array $prefilters
	 */
	public function __construct(A_Controller_Mapper $mapper, $error_action = null, array $prefilters = array()) {
    	$this->_mapper = $mapper;
    	$this->_error_action = $error_action;
    	$this->_prefilters = $prefilters;
    }
	
    /**
     * Add a pre-dispatch action to run
     *
     * @param string $actionName
     * @param A_DL $route
     */
    public function addPreAction($actionName, $route) {
    	$this->_prefilters[$actionName] = $route;
    }
	
    /**
     * Add a pre-dispatch filter
     *
     * @param string $name
     * @param unknown_type $prefilter What is this?
     */
    public function addPreFilter($name, $prefilter) {
   		$this->_prefilters[$name] = $prefilter;
    }
	
    /**
     * Get stack of dispatched routes
     * 
     * @return array
     */
    public function getRoutes() {
		return $this->_routes;
    }
	
    /**
     * Check if an error has been raised during dispatching
     *
     * @return boolean
     */
    public function isError() {
		return $this->_error; 
    }
	
    /**
     * Start dispatch process
     *
     * @param A_Locator $locator
     * @return boolean Error
     */
    public function run($locator = null) {
		
    	if(!$locator instanceof A_Locator ){
    		$locator = new A_Locator();
    	}
    	
    	$this -> _locator = $locator;
    	
    	$locator -> set('Mapper', $this->_mapper); // set mapper in registry for mvc loader to use 
        
		$route = $this-> _mapper -> getRoute($locator); // Route is an A_DL instance
		$error_action = $this->_error_action;
        $n = 0;

        while ($route !== false) { // Continute dispatching until there are no more routes to dispatch
			$controllerName = $route -> getControllerName();
	        $actionName = $route -> getActionName();
			$dir = ($route -> dir == '') ? $this -> _mapper -> getPath() : $route -> dir;
	        $this-> _routes[] = $route;	// save history of routes dispatched
	        $classExists = $locator -> loadClass($controllerName, $dir);
			
	        if ($classExists) {
				$controllerName = str_replace('-', '_', $controllerName);
		        $controller = new $controllerName($locator);
				
		        if ($this->_prefilters) {// Perform pre-filtering operations
		        	$this -> preFilter($controller); 
				}
				
		        if(method_exists($controller,'dispatch')) { // Controller can handle its own dispatch process, delegating
		        	$route = $controller -> dispatch($actionName,$locator);
		        } else if (! method_exists($controller, $actionName)) { //Default action
					$actionName = $this->_mapper->default_method;
				} else if (method_exists($controller, $actionName)) { //Invoking action directly
					$route = $controller -> {$method}($locator);
				} else {
					$this->_error = self::METHOD_NOT_FOUND;
				}
			} else if ($error_action) {
				$route = $error_action;
				$error_action = null;
			} else if ($n == 0) {
				$this->_error = self::CLASS_NOT_LOADED;
			}
			++$n;
        }
		return $this->_error;
    }
    
    /**
     * Invoke pre-dispatch filters
     *
     * @param object $controller An action controller
     */
    protected function preFilter($controller) {
		foreach (array_keys($this->_prefilters) as $name) {
			if (is_object($this->_prefilters[$name])) {
				if (! ($this->_prefilters[$name] instanceof A_DL)) {
					// pass controller to DI object to modify
					$change_action = $this->_prefilters[$name]->run($controller);
				} elseif (method_exists($controller, $name)) {
					// pre-execute method if it exists 
					$change_action = $controller->{$name}($this -> _locator);
				} else {
					$change_action = null;
				}
				if ($change_action) {
					if (is_object($change_action)) {
						$action = $change_action;
					} elseif (is_object($this->_prefilters[$name])) {
						$action = $this->_prefilters[$name];
					} else {
						$action = $this->_error_action;
					}
					continue 2;
				}
			} elseif (is_string($this->_prefilters[$name]) && function_exists($this->_prefilters[$name])) {
				$func = $this->_prefilters[$name];
				$func($controller);
			}
		}
    }
}
