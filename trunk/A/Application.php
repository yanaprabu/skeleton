<?php
/**
 * Application.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	John Cartwright
 */

/**
 * A_Application
 *
 *  Wrap bootstrap in a class that has configuration settings
 *
 * @package A
 */
class A_Application
{

	protected $exception;
	protected $includePath = '';
	protected $configPath = array('config.ini', 'skeleton');
	protected $components = array();
	protected $loadComponents = array(
		'Locator'  => array('A_Locator', false),
		'Config'   => array('A_Config_Ini', true),
		'Pathinfo' => array('A_Http_Pathinfo', false),
		'Mapper'   => array('A_Controller_Mapper', false),
		'Request'  => array('A_Http_Request', true),
		'Response' => array('A_Http_Response', true),
		'Template' => array('A_Template_Include', true),
		'Session'  => array('A_Session', true),
		'Front'    => array('A_Controller_Front', false),
	);

	public function __construct($exception=null)
	{
		$this->exception = $exception;
	}

	/**
	 * Execute the controller(s) and render the response.
	 *
	 * @return rendered response
	 */
	public function run()
	{
		if (!$this->includePath) $this->setPath($_SERVER['DOCUMENT_ROOT']);
		set_include_path(get_include_path() . PATH_SEPARATOR . $this->includePath);
		array_walk($this->loadComponents, array($this, 'initialize'));

		$this->component('Pathinfo')->run($this->component('Request'));
		$this->component('Front')->run($this->component('Locator'));
		if (!$this->component('Response')->hasRenderer()) {
			$this->component('Response')->setRenderer($this->components('Template'));
		}
		return $this->component('Response')->render();
	}

	/**
	 * Get the specified regisetered component
	 *
	 * @param string $component Component name to get
	 */
	public function component($component)
	{
		if (!isset($this->components[$component])) {
			if ($this->component('Config')->useExceptions) {
	         	throw A_Exception::getInstance($this->exception, 'Component "'. $component .' does not exist in stack"');
			}
			return false;
		}
		return $this->components[$component];
	}

	/**
	 * Create and return a new configuration object with the set internal config file path, and loads the config file
	 *
	 * @param string $component Class of configuration object
	 */
	public function initConfig($component)
	{
		$config = new $component($this->configPath[0], $this->configPath[1]);
		return $config->loadFile();
	}

	public function initMapper($component)
	{
		$config = $this->component('Config');
		$defaultAction = array('', $config->defaultController, $config->defaultAction);
		return new $component($config->applicationPath, $defaultAction);
	}

	/**
	 * Create and return a new front controller
	 *
	 * @param string $component Class of front controller
	 */
	public function initFront($component)
	{
		$mapper = $this->component('Mapper');
		$config = $this->component('Config');
		$defaultAction = array('', $config->errorController, $config->errorAction);
		return new $component($mapper, $defaultAction);
	}

	/**
	 * Create and return a new session object
	 *
	 * @param string $component Class of session object
	 */
	public function initSession($component)
	{
		$session = new $component('A');
		$config = $this->component('Config');
	  	if ($config->sessionHandler == 'database') {
			#$session->setHandler(new A_Session_Handler_Database());
	  	} else {
  			#$session->setHandler(new A_Session_Handler_Filesystem($config->sessionPath));
	  	}
	  	return $session;
	}

	public function __call($method, $args)
	{
		// intercept component initialization
		if (substr($method, 0, 4) == 'init') {
			return new $args[0];
		}
		return false;
	}

	/**
	 * @param unknown_type $component
	 * @param unknown_type $object
	 * @param unknown_type $register
	 */
	public function set($component, $object, $register = true)
	{
		if (is_string($object)) $this->load($component);
		$this->loadComponents[$component] = array($object, (bool) $register);
		return $this;
	}

	/**
	 * Set the include path
	 *
	 * @param string $path
	 */
	public function setPath($path)
	{
		$this->includePath = $path;
		return $this;
	}

	protected function initialize($component, $key)
	{
		$key = ucfirst($key);
		if (!is_object($component[0])) {
			$this->load($component[0]);
			$factory = 'init'. ucfirst($key);
			$this->components[$key] = $this->$factory($component[0]);
		} else {
			$this->components[$key] = $component;
		}

		if ($component[1]) {
			$this->component('Locator')->set($key, $this->components[$key]);
		}
		return $this;
	}

	protected function load($class)
	{
		return include_once str_replace('_', DIRECTORY_SEPARATOR, $class).'.php';
	}

}
