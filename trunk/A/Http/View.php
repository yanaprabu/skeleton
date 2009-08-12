<?php
/**
 * Base MVC View class for a whole or partial HTTP response. Encapsulates headers, redirects,
 * character encoding, quoting, escaping, and content. 
 * 
 * @package A_Http 
 */

class A_Http_View {
	protected $data = array();
	protected $template = null;
	protected $template_type = 'templates';
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
	protected $paths = array();				// cache array of paths calculated by Mapper
	protected $helpers = array();
	protected $use_local_vars = true;
	
	public function __construct($locator=null) {
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

	public function setTemplate($template) {
		$this->template = $template;
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

	public function set($name, $value) {
		if ($value !== null) {
			$this->data[$name] = $value;
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
		// if Locator set by FC then we can get the Mapper
		if ($this->locator) {
			$mapper = $this->locator->get('Mapper');
			if ($mapper) {
				// get paths array if not cached
				if (! isset($this->paths[$this->template_type])) {
					$this->paths[$this->template_type] = $mapper->getPaths($this->template_type);
				}
				return $this->paths[$this->template_type][$this->template_scope] . $template . '.php';
			}
		}
		return $this->template_type . '/' . $template . '.php';
	}
	
	public function partial($template) {
		$template = $this->_getPath($template);
		return $this->escape_output ? $this->escape($this->_include($template)) : $this->_include($template);
	}
	
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
	
	public function render($template='') {
		if (! $template && $this->template) {
			$template = $this->template;
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
		return $this->escape_output ? $this->escape($this->content) : $this->content;
	}
	
	protected function _include() {
		ob_start();
		if ($this->use_local_vars) extract($this->data, EXTR_REFS);
		include func_get_arg(0);
		return ob_get_clean();
	}

	public function __get($name) {
		return $this->get($name);
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	public function __toString() {
		return $this->render();
	}

	protected function _load($scope=null) {
		if (isset($this->load)) {
			$this->load->load($scope);
		} else {
			include_once "A/Controller/Helper/Load.php";
			$this->load = new A_Controller_Helper_Load($this->locator, $this, $scope);
		}
		return $this->load;
	}
 
	protected function _flash($name=null, $value=null) {
		if (! isset($this->flash)) {
			include_once "A/Controller/Helper/Flash.php";
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
 
	protected function helper($name) {
		if (! isset($this->helpers[$name])) {
			$this->helpers[$name] = $this->load()->helper($name);
			$this->set($name, $this->helpers[$name]);
		}
		if (isset($this->helpers[$name])) {
			return $this->helpers[$name];
		}
	}

}
