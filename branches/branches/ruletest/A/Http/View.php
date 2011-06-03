<?php
/**
 * View.php
 *
 * @package  A_Http
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Http_View
 *
 * Base MVC View class for a whole or partial HTTP response. Encapsulates headers, redirects, character encoding, quoting, escaping, and content. 
 */
class A_Http_View {
	protected $data = array();
	protected $template = null;
	protected $template_type = 'templates';
	protected $template_path = 'templates';
	protected $template_scope = 'module';
	protected $content = '';				// buffer set manually or by render()
	protected $renderer = null;
	protected $headers = array();
	protected $cookies = array();
	protected $redirect = null;
	protected $escape_quote_style = ENT_QUOTES;
	protected $escape_output = false;
	protected $character_set = 'UTF-8';
	protected $locator = null;
	protected $load;
	protected $flash;
	protected $mapper_name = 'Mapper';		// name in registry
	protected $paths = array();				// cache array of paths calculated by Mapper
	protected $helpers = array();
	protected $helperClass = array(
		'datetime'=>'A_DateTime',
		'json'=>'A_Json',
		'pagination'=>'A_Pagination_View_Standard',
		'url'=>'A_Http_Helper_Url',
	);
	protected $use_local_vars = true;
	protected $errorMsg = array();
	
	public function __construct($locator=null) {
		$this->locator = $locator;
	}
	
	public function setLocator($locator) {
		$this->locator = $locator;
	}
	
	public function setCharacterSet($character_set) {
		$this->character_set = $character_set;
		return $this;
	}

	public function setQuoteStyle($escape_quote_style) {
		$this->escape_quote_style = $escape_quote_style;
		return $this;
	}

	public function setEscape($escape_output) {
		$this->escape_output = $escape_output;
		return $this;
	}

	public function useLocalVars($use_local_vars) {
		$this->use_local_vars = $use_local_vars;
		return $this;
	}

	public function setHeader($field, $param=null) {
		if (is_array($param)) {
			$this->headers[$field] = $param;
		} else {
			$this->headers[$field][0] = $param;
		}
		return $this;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function setCookie() {
		$args = func_get_args();
		$this->cookie[$args[0]] = $args;
		return $this;
	}

	public function getCookie($name) {
		if (isset($this->cookie[$name])) {
			return $this->cookie[$name];
		}
	}

	public function setRedirect($url) {
		$this->redirect = $url;
		return $this;
	}

	public function getRedirect() {
		return $this->redirect;
	}

	public function setContent($content) {
		$this->content = $content;
		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	public function setTemplate($template, $scope='') {
		$this->template = $template;
		if ($scope) $this->template_scope = $scope;
		return $this;
	}

	public function setTemplateScope($scope) {
		$this->template_scope = $scope;
		return $this;
	}

	public function setTemplatePath($path) {
		$this->template_path = $path;
		return $this;
	}

	public function getTemplate() {
		return $this->template;
	}

	public function setRenderer($renderer) {
		$this->renderer = $renderer;
		return $this;
	}

	public function hasRenderer() {
		return isset($this->renderer);
	}

	public function set($name, $value, $default=null) {
		if ($value !== null) {
			$this->data[$name] = $value;
		} elseif ($default !== null) {
			$this->data[$name] = $default;
		} else {
			unset($this->data[$name]);
		}
		return $this;
	}

	public function get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function has($name) {
		return isset($this->data[$name]);
	}

	public function escape($content, $escape_quote_style=null) {
		return htmlspecialchars($content, $escape_quote_style==null ? $this->escape_quote_style : $escape_quote_style, $this->character_set);
	}
	
	public function _getPath($template) {
		if (substr($template, -4, 4) != '.php') {
			$template .= '.php';
		}
		// if Locator set by FC then we can get the Mapper
		if (method_exists($this->locator, 'get')) {
			$mapper = $this->locator->get($this->mapper_name);
			if ($mapper) {
				// get paths array if not cached
				if (! isset($this->paths[$this->template_type])) {
					$this->paths[$this->template_type] = $mapper->getPaths($this->template_type);
				}
				return $this->paths[$this->template_type][$this->template_scope] . $template;
			}
		}
		return $this->template_path . '/' . $template;
	}
	
	/**
	 * include PHP template
	 */
	public function partial($template, $data=null) {
		$template = $this->_getPath($template);
		return $this->escape_output ? $this->escape($this->_include($template, $data)) : $this->_include($template, $data);
	}
	
	/**
	 * include PHP template for each value in array
	 */
	public function partialLoop($template, $name, $data=null) {
		$template = $this->_getPath($template);
		$str = '';
		if ($data) {
			// $name and $data set so each element in $data set to $name
			foreach ($data as $value) {
				$this->data[$name] = $value;
				$str .= $this->_include($template);
			}
		} else {
			// $name but not $data, so $name contains $data. set() to $keys in each element array
			foreach ($name as $data) {
				if (is_array($data)) {
					foreach ($data as $key => $value) {
						$this->data[$key] = $value;
					}
				}
				$str .= $this->_include($template);
			}
		}
		return $this->escape_output ? $this->escape($str) : $str;
	}
	
	/**
	 * short for $this->set($name, $this->partial($template, $data))
	 */
	public function setPartial($name, $template, $data=null) {
		return $this->set($name, $this->partial($template, $data));
	}
	
	/**
	 * short for $this->set($name, $this->partialLoop($template, $data_name, $data))
	 */
	public function setPartialLoop($name, $template, $data_name, $data=null) {
		return $this->set($name, $this->partialLoop($template, $name, $data));
	}
	
	public function render($template='', $scope='') {
		if (! $template && $this->template) {
			$template = $this->template;
		}
		if ($scope) $this->template_scope = $scope;
		if ($template) {
			$this->content = $this->_include($this->_getPath($template));
		} elseif ($this->renderer) {
			if ($this->data) {
				foreach ($this->data as $name => $value) {
					if (is_object($this->data[$name]) && method_exists($this->data[$name], 'render')) {
						$this->renderer->set($name, $this->data[$name]->render());
					} else {
						$this->renderer->set($name, $value);
					}
				}
			}
			if (method_exists($this->renderer, 'render')) {
				$this->content = $this->renderer->render();
			}
		}
		return $this->escape_output ? $this->escape($this->content) : $this->content;
	}
	
	/**
	 * _include($template_path, $data=null)
	 * include a file
	 * optional second parameter is data array to be extracted
	 */
	protected function _include() {
		if (func_num_args() > 0) {					// must have at least the template path
			ob_start();
			if ($this->use_local_vars && $this->data) {
				extract($this->data, EXTR_REFS);
			}
			if (func_num_args() > 1) {				// extract array of values if passed
				if (is_array(func_get_arg(1))) {
					extract(func_get_arg(1));
				}
			}
			include func_get_arg(0);
			return ob_get_clean();
		}
	}

	public function __get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	/**
	 * Allow calls directly to the renderer object's methods
	 */
	public function __call($name, $args) {
		if (method_exists($this->renderer, $name)) {
			return call_user_func_array(array($this->renderer, $name), $args);
		}
		// elseif $name is a helper then load it
		// else throw an error or exception
	}

	public function __toString() {
		return $this->render();
	}

	protected function _load($scope=null) {
		if (isset($this->load)) {
			$this->load->load($scope);
		} else {
			$this->load = new A_Controller_Helper_Load($this->locator, $this, $scope);
		}
		return $this->load;
	}
 
	protected function _flash($name=null, $value=null) {
		if (! isset($this->flash)) {
			$this->flash = new A_Controller_Helper_Flash($this->locator);
		}
		if ($name) {
			if ($value) {
				$this->flash->set($name, $value);
			} else {
				return $this->flash->get($name);
			}
		}
		return $this->flash;
	}
 
	public function setHelper($name, $helper) {
		if ($name) {
			$this->helpers[$name] = $helper;
		}
		return $this;
	}
 
	public function setHelperClass($name, $class) {
		if ($name) {
			$this->helperClass[$name] = $class;
		}
		return $this;
	}
 
	protected function helper($name) {
		if (! isset($this->helpers[$name])) {
			if (isset($this->helperClass[$name])) {
				$class = $this->helperClass[$name];
			} else {
				$class = $name;
			}
			$this->helpers[$name] = $this->locator->get('', $class, '', $this->locator);
		}
		if (isset($this->helpers[$name])) {
			return $this->helpers[$name];
		}
	}

	/**
	 * get error messages
	 */
	public function getErrorMsg($separator="\n") {
		$errormsg = $this->errorMsg;
		if ($this->load) {
			$errormsg = array_merge($errormsg, $this->_load($scope)->getErrorMsg(''));
		}
		if ($separator) {
			$errormsg = implode($separator, $this->errorMsg);
		}
		return $errormsg;
	}
	
}
