<?php
/**
 * View.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_View
 *
 * Base MVC View class for a whole or partial HTTP response. Encapsulates headers, redirects, character encoding, quoting, escaping, and content.
 *
 * @package A_Http
 */
class A_Http_View implements A_Renderer
{

	protected $data = array();
	protected $escape_fields = array();		// array of keys in $data to escape, values are true/false whether field is escaped
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
	protected $character_set = 'UTF-8';
	protected $locator = null;
	protected $load;
	protected $flash;
	protected $mapper_name = 'Mapper';		// name in registry
	protected $paths = array();				// cache array of paths calculated by Mapper
	protected $helpers = array();
	protected $helperClass = array(
		'datetime'=>'A_Datetime',
		'json'=>'A_Json',
		'pagination'=>'A_Pagination_View_Standard',
		'url'=>'A_Http_Helper_Url',
	);
	protected $use_local_vars = true;
	protected $errorMsg = array();

	public function __construct($locator=null)
	{
		$this->locator = $locator;
	}

	public function setLocator($locator)
	{
		$this->locator = $locator;
	}

	public function setCharacterSet($character_set)
	{
		$this->character_set = $character_set;
		return $this;
	}

	public function setQuoteStyle($escape_quote_style)
	{
		$this->escape_quote_style = $escape_quote_style;
		return $this;
	}

	public function useLocalVars($use_local_vars)
	{
		$this->use_local_vars = $use_local_vars;
		return $this;
	}

	public function setHeader($field, $param=null)
	{
		if (is_string($field)) {
			if (is_array($param)) {
				$this->headers[$field] = $param;
			} elseif ($param === null) {
				unset($this->headers[$field]);
			} else {
				$this->headers[$field] = array(0 => $param);
			}
		}
		return $this;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param Parameters the same as the PHP setcookie() function
	 */
	public function setCookie()
	{
		$args = func_get_args();
		if ($args) {
			$this->cookies[$args[0]] = $args;
		}
		return $this;
	}

	public function getCookie($name)
	{
		if (isset($this->cookies[$name])) {
			return $this->cookies[$name];
		}
	}

	public function setRedirect($url)
	{
		$this->redirect = $url;
		return $this;
	}

	public function getRedirect()
	{
		return $this->redirect;
	}

	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setTemplate($template, $scope='')
	{
		$this->template = $template;
		if ($scope) $this->template_scope = $scope;
		return $this;
	}

	public function setTemplateScope($scope)
	{
		$this->template_scope = $scope;
		return $this;
	}

	public function setTemplatePath($path)
	{
		$this->template_path = $path;
		return $this;
	}

	public function getTemplate()
	{
		return $this->template;
	}

	public function setRenderer($renderer)
	{
		$this->renderer = $renderer;
		return $this;
	}

	public function hasRenderer()
	{
		return isset($this->renderer);
	}

	public function set($name, $value, $default=null)
	{
		if ($value !== null) {
			$this->data[$name] = $value;
			if (isset($this->escape_fields[$name])) {
				$this->escape_fields[$name] = false;
			}
		} elseif ($default !== null) {
			$this->data[$name] = $default;
			if (isset($this->escape_fields[$name])) {
				$this->escape_fields[$name] = false;
			}
		} else {
			unset($this->data[$name]);
			unset($this->escape_fields[$name]);
		}
		return $this;
	}

	public function get($name)
	{
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function has($name)
	{
		return isset($this->data[$name]);
	}

	public function import($data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function escape($content, $escape_quote_style=null)
	{
		if (extension_loaded('mbstring')) {
			mb_substitute_character('none');
			$content = mb_convert_encoding($content, $this->character_set, $this->character_set);
		}
		return htmlspecialchars($content, $escape_quote_style==null ? $this->escape_quote_style : $escape_quote_style, $this->character_set);
	}

	public function setEscape($name, $value, $default=null)
	{
		$this->escape_fields[$name] = false;			// Register this to be escaped later. False because not yet escaped.
		$this->set($name, $value, $default);
	}

	/**
	 * @param $name mixed field name or array of field names to be escaped
	 */
	public function escapeField($names)
	{
		if (!is_array($names)) {
			$names = array($names);
		}
		foreach ($names as $name) {
			if (!isset($this->escape_fields[$name])) {		// skip if already registered
				$this->escape_fields[$name] = false;			// Register this to be escaped later. False because not yet escaped.
			}
		}
	}

	/**
	 *
	 */
	public function _escape()
	{
		foreach ($this->escape_fields as $field => $isEscaped) {
			if (!$isEscaped) {
				$this->data[$field] =$this->escape($this->data[$field]);
				$this->escape_fields[$field] = true;		// set to escaped
			}
		}
	}

	/**
	 *
	 */
	public function _escape_array($data, $escape_fields)
	{
		foreach ($escape_fields as $field) {
			$data[$field] = $this->escape($data[$field]);
		}
		return $data;
	}

	public function _getPath($template)
	{
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
	 * Include PHP template
	 *
	 * @param string $template
	 * @param mixed $data
	 * @return string
	 */
	public function partial($template, $data=null, $escape_fields=null)
	{
		$template = $this->_getPath($template);
		$str = $this->_include($template, $data, $escape_fields);
		return $str;
	}

	/**
	 * include PHP template for each value in array
	 *
	 * @param string $template
	 * @param string $name
	 * @param mixed $data
	 * @return string
	 */
	public function partialLoop($template, $name, $data=null, $escape_fields=null)
	{
		$template = $this->_getPath($template);
		$str = '';
		if ($data) {
			// $name and $data set so each element in $data set to $name
			foreach ($data as $value) {
				$str .= $this->_include($template, array($name=>$value), $escape_fields);
			}
		} else {
			$tmp = array();
			// $name but not $data, so $name contains $data. set() to $keys in each element array
			foreach ($name as $data) {
				$str .= $this->_include($template, $data, $escape_fields);
			}
			// restore original values
			foreach ($tmp as $key => $value) {
				$this->data[$key] = $value;
			}
		}
		return $str;
	}

	/**
	 * Convenience method to more easily set a partial template
	 *
	 * @param string $name
	 * @param string $template
	 * @param mixed $data
	 * @return @this
	 */
	public function setPartial($name, $template, $data=null)
	{
		$this->set($name, $this->partial($template, $data));
		return $this;
	}

	/**
	 * short for $this->set($name, $this->partialLoop($template, $data_name, $data))
	 */
	public function setPartialLoop($name, $template, $data_name, $data=null)
	{
		$this->set($name, $this->partialLoop($template, $data_name, $data));
		return $this;
	}

	public function render($template='', $scope='')
	{
		if (!$template && $this->template) {
			$template = $this->template;
		}
		if ($scope) {
			$this->template_scope = $scope;
		}
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
		return $this->content;
	}

	/*
	 * Include a PHP file, passing internal data to it as variables
	 * Note: no local variables are used in this function to keep the namespace clean for extracted variables
	 * @param $template - the name of the template file
	 * @param $data - optional array data for template: keys are field names
	 * @param $escaped_fields - optional array of field names to escape
	 */
	protected function _include(/* $template, $data=array(), $escaped_fields=array() */)
	{
		if (func_num_args() > 0) {											// must have at least the template path
			ob_start();
			if ($this->use_local_vars && $this->data) {
				$this->_escape();
				extract($this->data);
			}
			if ((func_num_args() > 1) && is_array(func_get_arg(1))) {		// array of values passed
				if ((func_num_args() > 2) && is_array(func_get_arg(2))) {	// array of fields to escaped passed
					extract($this->_escape_array(func_get_arg(1), func_get_arg(2)));
				} else {
					extract(func_get_arg(1));
				}
			}
			include func_get_arg(0);
			return ob_get_clean();
		}
	}

	public function __get($name)
	{
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function __set($name, $value)
	{
		return $this->set($name, $value);
	}

	/**
	 * Allow calls directly to the renderer object's methods
	 */
	public function __call($name, $args)
	{
		if (method_exists($this->renderer, $name)) {
			return call_user_func_array(array($this->renderer, $name), $args);
		}
		// TODO elseif $name is a helper then load it
		// else throw an error or exception
	}

	public function __toString()
	{
		return $this->render();
	}

	protected function _load($scope=null)
	{
		if (isset($this->load)) {
			$this->load->load($scope);
		} else {
			$this->load = new A_Controller_Helper_Load($this->locator, $this, $scope);
		}
		return $this->load;
	}

	protected function _flash($name=null, $value=null)
	{
		if (!isset($this->flash)) {
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

	public function setHelper($name, $helper)
	{
		if ($name) {
			$this->helpers[$name] = $helper;
		}
		return $this;
	}

	public function setHelperClass($name, $class)
	{
		if ($name) {
			$this->helperClass[$name] = $class;
		}
		return $this;
	}

	protected function helper($name)
	{
		if (!isset($this->helpers[$name])) {
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
	 * Get error messages
	 *
	 * @param string $separator Separator between errors, set to null for an array
	 * @return string|array
	 */
	public function getErrorMsg($separator="\n")
	{
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
