<?php

class A_Http_View {
	protected $data = array();
	protected $renderer = null;
	protected $headers = array();
	protected $cookies = array();
	protected $redirect = null;
	protected $content = '';
	protected $escape_quote_style = ENT_QUOTES;
	protected $escape_output = false;
	protected $character_set = 'UTF-8';
	protected $locator = null;
	protected $loader = null;
	
	public function __construct($locator=null) {
		$this->locator = $locator;
	}
	
	public function setCharacterSet($character_set) {
		$this->character_set = $character_set;
	}

	public function setQuoteStyle($escape_quote_style) {
		$this->escape_quote_style = $escape_quote_style;
	}

	public function setEscape($escape_output) {
		$this->escape_output = $escape_output;
	}

	public function setHeader($field, $param=null) {
		 if (is_array($param)) {
			$this->headers[$field] = $param;
		 } else {
			$this->headers[$field][0] = $param;
		 }
	}

	public function getHeaders() {
		 return $this->headers;
	}

	public function setCookie() {
		 $args = func_get_args();
		 $this->cookie[$args[0]] = $args;
	}

	public function getCookie($name) {
		 if (isset($this->cookie[$name])) {
		 	return $this->cookie[$name];
		 }
	}

	public function setRedirect($url) {
		 $this->redirect = $url;
	}

	public function getRedirect() {
		 return $this->redirect;
	}

	public function setContent($content) {
		 $this->content = $content;
	}

	public function getContent() {
		 return $this->content;
	}

	public function setRenderer($renderer) {
		 $this->renderer = $renderer;
	}

	public function hasRenderer() {
		 return isset($this->renderer);
	}

	public function set($name, $value) {
		 $this->data[$name] = $value;
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
	
	public function render() {
		if ($this->renderer) {
			if ($this->data) {
				foreach ($this->data as $name => $value) {
					if (method_exists($this->data[$name], 'render')) {
						$this->renderer->set($name, $this->data[$name]->render());
					} else {
						$this->renderer->set($name, $value);
					}
				}
			}
		}
		if (method_exists($this->renderer, 'render')) {
			$this->content = $this->renderer->render();
		}
		if (! $this->escape_output) {
			return $this->content;
		} else {
			return $this->escape($this->content);
		}
	}
	
	protected function load($module=null) {
		if (! $this->loader) {
		    include_once 'A/Controller/Action/Loader.php';
			$this->loader = new A_Controller_Action_Loader($this->locator);
		}
		return $this->loader->load($module);
	}

}
