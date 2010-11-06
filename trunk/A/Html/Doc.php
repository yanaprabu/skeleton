<?php
/**
 * Generate HTML document
 *
 * @package A_Html
 */

class A_Html_Doc {
	
	protected $_config = array(
					'doctype' => '',
					'title' => '',
					'base' => '',
					'meta' => array(),
					'links' => array(),
					'style_links' => array(),
					'stylesheets' => array(),
					'styles' => array(),
					'script_links' => array(),
					'scripts' => array(),
					'body_attrs' => array(),
	); 
	protected $_head = null;
	protected $_body = '';
	
	/*
	* optional configuration array
	*/
	public function __construct($config=array()) {
		if ($config) {
			$this->config($config);
		}
	}
	
	public function config($config) {
		$this->_config = array_merge($this->_config, $config);
		return $this;
	}
	
	/*
	* name=string, value=string or renderer
	*/
	public function setDoctype($doctype=null) {
		$this->_config['doctype'] = $doctype;
		return $this;
	}
	
	/*
	* name=string, value=string or renderer
	*/
	public function renderDoctype($doctype=null) {
		$doctype = $doctype === null ? $this->_config['doctype'] : $doctype;
		$renderer = new A_Html_Doctype($doctype);
		return $renderer->render();
	}
	
	public function set($name, $value) {
		if (isset($this->_config[$name])) {
			if (is_array($this->_config[$name])) {
				$this->_config[$name][] = $value;
			} else {
				$this->_config[$name] = $value;
			}
		} elseif ($name == 'content') {					// how do we make this compatable with A_Http_Response usage?
			$this->_body = $value;
		}
		return $this;
	}

	public function head() {
		if (! $this->_head) {
			$this->_head = new A_Html_Head($this->_config);
		}
		return $this->_head;
	}

	
	public function __call($name, $args) {
		if (method_exists($this->head(), $name)) {
			return call_user_func_array(array($this->_head, $name), $args);
		}
		trigger_error("Method $name not found in class A_Html_Head called by A_Html_Doc. ", E_USER_ERROR);
	}

	
	public function setBodyAttr($attr, $value) {
		$this->_config['body_attrs'][$attr] = $value;
		return $this;
	}
	
	/**
	 * removeBodyAttr()
	 * Remove an attribute assigned to the body element with setBodyAttr()
	 * @param mixed $attr The attribute to remove
	 */
	public function removeBodyAttr($attr) {
		if (isset($this->_config['body_attrs'][$attr])) {
			unset($this->_config['body_attrs'][$attr]);
		}
		return $this;
	}

	public function setBody($body) {
		return $this->_body = $body;
	}

	/**
	 * Compatability with Response/View
	 */
	public function setContent($body) {
		return $this->_body = $body;
	}

	public function setRenderer($body) {
		return $this->_body = $body;
	}

	/**
	 * Rendering methods
	 */
	
	public function renderTitle() {
		return $this->_config['title'] ? "<title>{$this->_config['title']}</title>\n" : '';
	}

	public function renderBase() {
		return $this->_config['base'] ? "<base href=\"{$this->_config['base']}\"/>\n" : '';
	}


	public function renderBodyAttrs() {
		$str = '';
		foreach ($this->_config['body_attrs'] as $key => $value) {
			$str .= " $key=\"$value\"";
		}
		return $str;
	}

	public function renderBody() {
		if (is_object($this->_body) && method_exists($this->_body, 'render')) {
			return $this->_body->render();
		} else {
			return $this->_body;
		}
	}

	/*
	* name=string, value=string or renderer
	*/
	public function render($attr=array(), $content=null) {
		$html = $this->renderDoctype();
		$html .= "<html>\n";
		$html .= $this->head()->render();
		$html .= "<body";
		$html .= $this->renderBodyAttrs();
		$html .= ">\n";
		$html .= $this->renderBody();
		$html .= "</body>\n</html>\n";
		return $html;
	}

	public function __toString() {
		$this->render();
	}
}
