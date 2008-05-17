<?php

class A_Html_Tag {
	protected $_attr = array();
	
	public function __construct($attr=array(), $value=null) {
		$this->_attr = $attr;
		if ($value !== null) {
			$this->_attr['value'] = $value;
		}
	}
	
	public function mergeAttr(&$attr) {
		if (isset($this) && isset($this->_attr) && is_array($attr)) {
			$attr = array_merge($this->_attr, $attr);
		}
	}
	
	public function defaultAttr(&$attr, $defaults=array()) {
		foreach($defaults as $key => $value) {
			if (! isset($attr[$key])) {
				$attr[$key] = $value;
			}
		}
	}
	
	public function removeAttr(&$attr, $key) {
		if ($key) {
			unset($attr[$key]);
			if (isset($this->_attr[$key])) {
				unset($this->_attr[$key]);
			}
		}
	}
	
	/*
	 * $tag - name of tag
	 * $attr - array of attributes 
	 * $content - determines if the tag has a closing tag - defined yes, null no
	 *          - a string, an object with a render() method or an array containing any mix of those
	 * e.g. render('div', array('id'=>'foo'), 'bar') generates <div id="foo">bar</div>
	 * e.g. render('img', array('src'=>'foo.jpg', 'alt'=>'bar')) generates <img src="foo.jpg" alt="bar"/>
 	 */
	public function render($tag, $attr=array(), $content=null) {
		self::mergeAttr($attr);

		$str = "<$tag";
		foreach ($attr as $name=>$value) {
			$str .= " $name=\"$value\"";
		}
		if ($content === null) {
			$str .= '/>';
		} elseif (is_array($content)) {
			$str .= '>';
			foreach ($content as $c) {
				$str .= is_string($c) ? $c : $c->render();
			}
		} else {
			$str .= '>' . (is_string($content) ? $content : $content->render()) . "</$tag>";
		}
		return $str;
	}

}
