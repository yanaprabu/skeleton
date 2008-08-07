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
	const NO_ERROR = '';
	const NO_MAPPER = 'Mapper object not available';
	const CLASS_NOT_LOADED = 'Requested class not loaded';
	const METHOD_NOT_FOUND = 'Requested method not found';
	
	/**
	 * The front controller mapper object
	 * @var A_Mapper
	 */
	protected $mapper = null;
	
	/**
	 * A_DL object with the route for when dispatch error occurs
	 * @var A_DL
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
	 * @param A_DL $error_route for when dispatch error occurs
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
	public function addPreFilter($name, $filter) {
   		$this->preFilters[$name] = $filter;
	}
	
	/**
	 * Add a post-dispatch filter
	 *
	 * @param string $name
	 * @param object with run($controller) method
	 */
	public function addPostFilter($name, $filter) {
   		$this->postFilters[$name] = $filter;
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
			$locator = new A_Locator();
		}
		$this->locator = $locator;
		
		if ($this->mapper) {
			$locator->set('Mapper', $this->mapper); // set mapper in registry for mvc loader to use 
		} else {
			return self::NO_MAPPER;
		}
		
		$route = $this->mapper->getRoute($locator); // Route is an A_DL instance
		$error_route = $this->errorRoute;
		
		$n = 0;
		while ($route) {
			$class  = $this->mapper->formatClass($route->class);
			$method = $this->mapper->formatMethod($route->method);
			if ($route->dir == '') {
				$dir = $this->mapper->getPath();
			} else {
				$dir = $route->dir;
			}
			$this->routeHistory[] = $route;	// save history of routes
			$route = null;
			$result = $locator->loadClass($class, $dir);
			if ($result) {
				$class = str_replace('-', '_', $class);
				$controller = new $class($locator);
	
				if ($this->preFilters) {
					$this->runFilters($controller, $this->preFilters);
				}
				
				if (method_exists($controller, $this->dispatchMethod)) {
//					$method = $this->dispatchMethod;
					$route = $controller->{$this->dispatchMethod}($locator, $method);
				} else {
					if (! method_exists($controller, $method)) {
						$method = $this->mapper->default_method;
					}
					if (method_exists($controller, $method)) {
						$route = $controller->{$method}($locator);
					} else {
						$this->error = self::NO_METHOD;		// no known method to dispatch
					}
				}
	
				if ($this->postFilters) {
					$this->runFilters($controller, $this->postFilters);
				}
			} elseif ($error_route) {
				$route = $error_route;
				$error_route = null;
			} elseif ($n == 0) {
				$this->error = self::CLASS_NOT_LOADED;			// cannot load class and not error route 
			}
			++$n;
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
		foreach (array_keys($filters) as $name) {
			if (is_object($filters[$name])) {
				if (! ($filters[$name] instanceof A_DL)) {
					// pass controller to DI object to modify
					$change_route = $filters[$name]->run($controller);
				} elseif (method_exists($controller, $name)) {
					// pre-execute method if it exists 
					$change_route = $controller->{$name}($this->locator);
				} else {
					$change_route = null;
				}
				if ($change_route) {
					if (is_object($change_route)) {
						$route = $change_route;
					} elseif (is_object($filters[$name])) {
						$route = $filters[$name];
					} else {
						$route = $this->errorRoute;
					}
					continue 2;
				}
			} elseif (is_string($filters[$name]) && function_exists($filters[$name])) {
				$func = $filters[$name];
				$func($controller);
			}
		}
	}
}
