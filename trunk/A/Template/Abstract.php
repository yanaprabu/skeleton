<?php
/**
 * Base Template class with template and get/set/has functionality  
 * 
 * @package A_Tempalte 
 */

abstract class A_Template_Abstract {
	protected $template = '';
	protected $data = array();
	protected $filename = '';
	protected $escape_quote_style = ENT_QUOTES;
	protected $escape_output = false;
	protected $character_set = 'UTF-8';
	
	public function __construct($filename='', $data=array()) {
		$this->filename = $filename;
		if ($data) {
			$this->import($data);
		}
	}
	
	public function setTemplate($template) {
		$this->template = $template;
		return $this;
	}
	
	public function setFilename($filename) {
		$this->filename = $filename;
		return $this;
	}
	
	public function clear() {
		$this->data = array();
		return $this;
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

	public function escape($content, $escape_quote_style=null) {
		return htmlspecialchars($content, $escape_quote_style==null ? $this->escape_quote_style : $escape_quote_style, $this->character_set);
	}
	
	public function renderArray($array, $block='') {
	   	$str = '';
	   	foreach ($array as $key1 => $value1) {
	   		if (is_array($value1)) {
		   		foreach ($value1 as $key2 => $value2) {
			   		$this->set($key2, $value2);
			   	}
		   		$str .= $this->render($block);
	   		} else {
			   	$this->set($key1, $value1);
		   	}
	   	}
	   	if (! isset($key2)) {
		   	$str .= $this->render($block);
	   	}
	   	return $str;
	}

	public function get($name) {
		return (isset($this->data[$name]) ? $this->data[$name] : null);
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

	public function import($data) {
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function has($name) {
		return isset($this->data[$name]);
	}

	public function __toString() {
		return $this->render();
	}

}
