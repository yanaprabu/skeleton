<?php
/**
 * Script.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Script
 * 
 * Generate HTML <script> tag
 */
class A_Html_Style {
	
	protected $_config = array(
					'script_links' => array(),
					'scripts' => array(),
	); 
	protected $_label = '';
	
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

	public function _addConfig($name, $data) {
		if ($name && $data) {
			if ($this->_label) {
				$data['label'] = $this->_label;
			}
			if ($this->_before === null) {
				$this->_config[$name][] = $data;
			} elseif ($this->_before == '') {
				$this->_config[$name] = array_merge(array($data), $this->_config[$name]);
			} else {
				$move = false;
				foreach ($this->_config[$name] as $key => $data) {
					if (isset($data['label']) && ($data['label'] == $this->_before)) {
						array_splice($this->_config[$name], $key, 0,array($data));
						break;
					}
				}
			}
		}
		$this->_label = '';
		$this->_before = null;
		return $this;
	}
	
	public function addScript($script, $type='text/javascript') {
		if ($script) {
			$this->_addConfig('scripts', array('script'=>$script, 'type'=>$type));
		}
		return $this;
	}
	 
	public function addScriptLink($url, $type='text/javascript') {
		if ($url) {
			$this->_addConfig('script_links', array('src'=>$url, 'type'=>$type));
		}
		return $this;
	}
	 
	public function renderScriptLinks() {
		$str = '';
		foreach ($this->_config['script_links'] as $data) {
			$str .= "<script type=\"{$data['type']}\" src=\"{$data['src']}\"></script>\n";
		}
		return $str;
	}

	public function renderScripts() {
		$str = '';
		foreach ($this->_config['scripts'] as $data) {
			$str .= "<script type=\"{$data['type']}\">\n{$data['script']}\n</script>\n";
		}
		return $str;
	}

	/*
	* name=string, value=string or renderer
	*/
	public function render($attr=array(), $content=null) {
		$html = '';
		$html .= $this->renderScriptLinks();
		$html .= $this->renderScripts();
		return $html;
	}

	public function __toString() {
		$this->render();
	}
}
