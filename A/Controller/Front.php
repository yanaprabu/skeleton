<?php

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
	const NO_ERROR = '';
	const NO_MAPPER = 'Mapper object not available';
	const NO_CLASS = 'Requested class not loaded';
	const NO_METHOD = 'Requested method not found';
	
	/**
	 * The front controller mapper object
	 * @var A_Mapper
	 */
	protected $mapper = null;
	
	/**
	 * array with the dir/class/method/args for when dispatch error occurs
	 * @var array
	 */
	protected $errorRoute;
	
	/*
	 * name of method for Actions with dipatcher
	 * @var string
	 */
	protected $dispatchMethod = 'dispatch';
	
	/**
	 * Pre-dispatch filters
	 * @var array
	 */
	protected $preFilters = null;
	
	/**
	 * Post-dispatch filters
	 * @var array
	 */
	protected $postFilters = null;
	
	/**
	 * The front controller locator object
	 * @var A_Locator
	 */
	protected $locator = null;
	
	/**
	 * Stack of dispatched routes
	 * @var array
	 */
	protected $routeHistory = array();	
	
	/**
	 * Error indicator
	 * @var int
	 */
	protected $error = self::NO_ERROR;
	
	/**
	 * Class constructor
	 *
	 * @param A_Controller_Mapper $mapper
	 * @param array $error_route containing dir/class/method/args for when dispatch error occurs
	 */
	public function __construct(A_Controller_Mapper $mapper, $error_route) {
		$this->mapper = $mapper;
		$this->errorRoute = $error_route;
	}
	
	/**
	 * Add a pre-dispatch filter
	 *
	 * @param string $name
	 * @param object with run($controller) method
	 */
	public function addPreFilter($filter) {
   		$this->preFilters[] = $filter;
	}
	
	/**
	 * Add a post-dispatch filter
	 *
	 * @param string $name
	 * @param object with run($controller) method
	 */
	public function addPostFilter($filter) {
   		$this->postFilters[] = $filter;
	}
	
	/**
	 * Get stack of dispatched routes
	 * 
	 * @return array
	 */
	public function getRoutes() {
		return $this->routeHistory;
	}
	
	/**
	 * Check if an error has been raised during dispatching
	 *
	 * @return boolean
	 */
	public function isError() {
		return $this->error; 
	}
	
	/**
	 * Start dispatch process
	 *
	 * @param A_Locator $locator
	 * @return boolean Error
	 */
	public function run($locator = null) {
		
		if(! $locator){
			include_once 'A/Locator.php';
			$locator = new A_Locator();
		}
		if (! $locator->has('Request')) {
			include_once 'A/Http_Request.php';
			$locator->set('Request', new A_Http_Request());
		}
		
		if (! $this->mapper) {
			include_once 'A/Controller/Mapper.php';
			$this->mapper = new A_Controller_Mapper();
		}
		$locator->set('Mapper', $this->mapper); // set mapper in registry for mvc loader to use 
		$this->locator = $locator;
		
		$route = $this->mapper->getRoute($locator->get('Request'));
		$error_route = $this->errorRoute;
		
		$n = -1;
		while ($route) {
			$this->mapper->setRoute($route); // set dir/class/method
			++$n;
			$class  = $this->mapper->getFormattedClass();
			$method = $this->mapper->getFormattedMethod();
			$dir = $this->mapper->getPath();
			$this->routeHistory[] = $route;	// save history of routes
			$route = null;
			$result = $locator->loadClass($class, $dir);
			if ($result) {
				$class = str_replace('-', '_', $class);
				$controller = new $class($locator);
	
				if ($this->preFilters) {
					// run pre filtes and check if forward
					$change_route = $this->runFilters($controller, $this->preFilters);
					if ($change_route !== null) {
						// if filter forwarded then set new route and reloop for mapping
						$route = $change_route;
						continue;
					}
				}
				
				if (method_exists($controller, $this->dispatchMethod)) {
					$route = $controller->{$this->dispatchMethod}($locator, $method);
				} else {
					if (! method_exists($controller, $method)) {
						$method = $this->mapper->getDefaultMethod();
					}
					if (method_exists($controller, $method)) {
						$route = $controller->{$method}($locator);
					} else {
						$this->error = self::NO_METHOD;		// no known method to dispatch
					}
				}
	
				if ($this->postFilters) {
					$change_route = $this->runFilters($controller, $this->postFilters);
					if ($change_route !== null) {
						// if filter forwarded then set route to loop again
						$route = $change_route;
					}
				}
			} elseif ($error_route) {
				$route = $error_route;
				$error_route = null;
			} elseif ($n == 0) {
				$this->error = self::NO_CLASS;			// cannot load class and not error route 
			}
		}
		return $this->error;
	}
	
	/**
	 * Invoke filters
	 *
	 * @param object $controller an action controller
	 * @filters array of filter objects with run($controller) method
	 * 	 */
	protected function runFilters($controller, $filters) {
		foreach ($filters as $filter) {
			$change_route = null;
			switch (gettype($filter)) {
				case 'object': 
					if (method_exists($filter, 'run')) {
						// pass controller to DI object to modify
						$change_route = $filter->run($controller);
					}
					break;
				case 'string':
					if (method_exists($controller, $filter)) {
						// pre-execute method if it exists 
						$change_route = $controller->{$filter}($this->locator);
					}
					break;
				case 'array':
					$change_route = call_user_func($filter, $controller);
					break;
			}
			// return value is forward object or true
			if ($change_route) {
				return $change_route;
			}
		}
	}
}
